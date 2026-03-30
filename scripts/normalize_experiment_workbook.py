from __future__ import annotations

import sys
import zipfile
import xml.etree.ElementTree as ET
from pathlib import Path


NS = {"a": "http://schemas.openxmlformats.org/spreadsheetml/2006/main"}


def col_letters(cell_ref: str) -> str:
    out = ""
    for ch in cell_ref:
        if ch.isalpha():
            out += ch
        else:
            break
    return out


def col_to_num(col: str) -> int:
    n = 0
    for ch in col:
        n = n * 26 + (ord(ch.upper()) - ord("A") + 1)
    return n


def read_sheet(zf: zipfile.ZipFile, name: str) -> list[list[str]]:
    root = ET.fromstring(zf.read(name))
    rows: list[tuple[int, dict[int, str]]] = []

    for row in root.find("a:sheetData", NS):
        values: dict[int, str] = {}
        max_col = 0

        for cell in row.findall("a:c", NS):
            ref = cell.attrib.get("r", "")
            col_num = col_to_num(col_letters(ref))
            max_col = max(max_col, col_num)
            value = ""

            inline = cell.find("a:is", NS)
            raw = cell.find("a:v", NS)

            if cell.attrib.get("t") == "inlineStr" and inline is not None:
                value = "".join(node.text or "" for node in inline.iterfind(".//a:t", NS))
            elif raw is not None and raw.text is not None:
                value = raw.text

            values[col_num] = value

        rows.append((max_col, values))

    width = max((max_col for max_col, _ in rows), default=0)
    return [[values.get(i, "") for i in range(1, width + 1)] for _, values in rows]


def clean_text(value: str) -> str:
    return str(value).replace("\t", " ").replace("\r", " ").replace("\n", " ")


def normalize_language(value: str) -> str:
    return {
        "english": "en",
        "arabic": "ar",
    }.get(value, value)


def normalize_actual_source(value: str) -> str:
    return {
        "fake": "AI",
        "ai": "AI",
        "real": "real",
    }.get(value.lower(), value)


def hesitation_score_ms(decision_time_ms: str, pause_count: str, rewatch_count: str) -> str:
    try:
        decision = max(int(float(decision_time_ms or 0)), 0)
        pauses = max(int(float(pause_count or 0)), 0)
        rewatches = max(int(float(rewatch_count or 0)), 0)
    except ValueError:
        return ""

    score = round((decision / 1000) + (pauses * 1.5) + (rewatches * 2), 2)
    return f"{score:.2f}"


def write_tsv(path: Path, rows: list[list[str]]) -> None:
    with path.open("w", encoding="utf-8", newline="") as handle:
        for row in rows:
            handle.write("\t".join(clean_text(cell) for cell in row) + "\n")


def main() -> int:
    if len(sys.argv) != 2:
        print("Usage: python scripts/normalize_experiment_workbook.py <xlsx_path>")
        return 1

    workbook_path = Path(sys.argv[1])
    if not workbook_path.exists():
        print(f"Workbook not found: {workbook_path}")
        return 1

    with zipfile.ZipFile(workbook_path) as zf:
        participants = read_sheet(zf, "xl/worksheets/sheet1.xml")
        responses = read_sheet(zf, "xl/worksheets/sheet2.xml")

    participant_header = participants[0]
    participant_rows = participants[1:]
    response_header = responses[0]
    response_rows = responses[1:]

    p_index = {name: idx for idx, name in enumerate(participant_header)}
    r_index = {name: idx for idx, name in enumerate(response_header)}

    for row in participant_rows:
        row[p_index["preferred_language"]] = normalize_language(row[p_index["preferred_language"]])

    for row in response_rows:
        row[r_index["actual_source"]] = normalize_actual_source(row[r_index["actual_source"]])
        row[r_index["q_uncertainty_question"]] = "How uncertain are you?"
        row[r_index["hesitation_score"]] = hesitation_score_ms(
            row[r_index["decision_time_ms"]],
            row[r_index["pause_count"]],
            row[r_index["rewatch_count"]],
        )

    participants_out = workbook_path.with_name("participants_normalized.tsv")
    responses_out = workbook_path.with_name("responses_normalized.tsv")

    write_tsv(participants_out, [participant_header, *participant_rows])
    write_tsv(responses_out, [response_header, *response_rows])

    print(participants_out)
    print(responses_out)
    print(f"participants_rows={len(participants)}")
    print(f"responses_rows={len(responses)}")
    return 0


if __name__ == "__main__":
    raise SystemExit(main())

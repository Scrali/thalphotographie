import json
from pathlib import Path

PHOTOS_DIR = Path("photos")
OUT_FILE = PHOTOS_DIR / "gallery.auto.json"

# Dossiers à ignorer
IGNORE_DIRS = {
    ".git",
    ".github",
    "__MACOSX",
}

# Extensions images acceptées
EXTS = {".jpg", ".jpeg", ".png", ".webp", ".gif"}

def is_image(p: Path) -> bool:
    return p.is_file() and p.suffix.lower() in EXTS

def main():
    data = {}

    if not PHOTOS_DIR.exists():
        raise SystemExit("photos/ introuvable")

    for cat_dir in sorted(PHOTOS_DIR.iterdir()):
        if not cat_dir.is_dir():
            continue
        if cat_dir.name in IGNORE_DIRS:
            continue

        # ignore aussi les dossiers "scripts" si tu en mets dans photos/
        if cat_dir.name.lower() in {"scripts"}:
            continue

        files = [p.name for p in sorted(cat_dir.iterdir()) if is_image(p)]
        if files:
            data[cat_dir.name] = files

    # ÉCRITURE PROPRE : overwrite + JSON valide
    OUT_FILE.write_text(json.dumps(data, ensure_ascii=False, indent=2), encoding="utf-8")
    print(f"Wrote {OUT_FILE} with {len(data)} categories.")

if __name__ == "__main__":
    main()
import json
import shutil
import sys
import tempfile
import unittest
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parents[1] / "scripts"))

import build_gallery_auto as bga


class IsImageTests(unittest.TestCase):
    def setUp(self):
        self.tmp_dir = Path(tempfile.mkdtemp())
        self.addCleanup(shutil.rmtree, self.tmp_dir, ignore_errors=True)

    def test_accepts_known_image_extensions(self):
        img = self.tmp_dir / "photo.JPG"
        img.write_bytes(b"fake")
        self.assertTrue(bga.is_image(img))

    def test_rejects_non_image_files(self):
        doc = self.tmp_dir / "notes.txt"
        doc.write_text("hello")
        self.assertFalse(bga.is_image(doc))

    def test_rejects_directories_even_with_image_extension(self):
        fake_dir = self.tmp_dir / "subdir.jpg"
        fake_dir.mkdir()
        self.assertFalse(bga.is_image(fake_dir))


class MainTests(unittest.TestCase):
    def setUp(self):
        self.tmp_dir = Path(tempfile.mkdtemp())
        self.addCleanup(shutil.rmtree, self.tmp_dir, ignore_errors=True)
        self.photos_dir = self.tmp_dir / "photos"
        self.out_file = self.photos_dir / "gallery.auto.json"

        self._orig_photos_dir = bga.PHOTOS_DIR
        self._orig_out_file = bga.OUT_FILE
        bga.PHOTOS_DIR = self.photos_dir
        bga.OUT_FILE = self.out_file
        self.addCleanup(self._restore_module_state)

    def _restore_module_state(self):
        bga.PHOTOS_DIR = self._orig_photos_dir
        bga.OUT_FILE = self._orig_out_file

    def test_groups_images_by_category_and_ignores_non_images(self):
        (self.photos_dir / "Portraits").mkdir(parents=True)
        (self.photos_dir / "Portraits" / "a.jpg").write_bytes(b"fake")
        (self.photos_dir / "Portraits" / "notes.txt").write_text("skip me")

        bga.main()

        result = json.loads(self.out_file.read_text(encoding="utf-8"))
        self.assertEqual(result, {"Portraits": ["a.jpg"]})

    def test_ignores_scripts_subfolder_inside_photos(self):
        (self.photos_dir / "scripts").mkdir(parents=True)
        (self.photos_dir / "scripts" / "ignored.jpg").write_bytes(b"fake")

        bga.main()

        result = json.loads(self.out_file.read_text(encoding="utf-8"))
        self.assertEqual(result, {})

    def test_raises_when_photos_dir_missing(self):
        with self.assertRaises(SystemExit):
            bga.main()


if __name__ == "__main__":
    unittest.main()

import unittest
from datetime import datetime

def overlap(a_start, a_end, b_start, b_end):
    return a_start < b_end and b_start < a_end


def prosesPeminjamanBaru(request, existing):
    for ex in existing:
        if overlap(
            request["start"], request["end"],
            ex["start"], ex["end"]
        ):
            if request["priority"] > ex["priority"]:
                return "Ditolak oleh sistem (prioritas lebih rendah)"

            if request["priority"] == ex["priority"]:
                if request["createdAt"] > ex["createdAt"]:
                    return "Ditolak oleh sistem (lebih lambat mengajukan)"

            return "Ditolak (peminjaman lama tidak dapat diganggu)"

    return "Lolos Greedy, menunggu verifikasi Admin"


class TestPeminjaman(unittest.TestCase):

    def setUp(self):
        self.existing = [
            {
                "start": datetime(2025, 11, 30, 9, 0),
                "end": datetime(2025, 11, 30, 12, 0),
                "priority": 3, #dosen
                "createdAt": datetime(2025, 11, 20, 10, 0),
            }
        ]

        self.test_cases = [
            {
                "name": "Ditolak karena prioritas lebih rendah",
                "request": {
                    "start": datetime(2025, 11, 30, 10, 0),
                    "end": datetime(2025, 11, 30, 11, 0),
                    "priority": 4,  # USER
                    "createdAt": datetime(2025, 11, 25, 9, 0)
                },
                "expected": "Ditolak oleh sistem (prioritas lebih rendah)"
            },
            {
                "name": "Ditolak karena lebih lambat mengajukan",
                "request": {
                    "start": datetime(2025, 11, 30, 10, 0),
                    "end": datetime(2025, 11, 30, 11, 0),
                    "priority": 3, #dosen
                    "createdAt": datetime(2025, 11, 22, 10, 0)
                },
                "expected": "Ditolak oleh sistem (lebih lambat mengajukan)"
            },
            {
                "name": "Ditolak (peminjaman lama tidak dapat diganggu)",
                "request": {
                    "start": datetime(2025, 11, 30, 10, 0),
                    "end": datetime(2025, 11, 30, 11, 0),
                    "priority": 1, #super admin
                    "createdAt": datetime(2025, 11, 25, 9, 0)
                },
                "expected": "Ditolak (peminjaman lama tidak dapat diganggu)"
            },
            {
                "name": "Lolos Greedy",
                "request": {
                    "start": datetime(2025, 11, 30, 13, 0),
                    "end": datetime(2025, 11, 30, 14, 0),
                    "priority": 2, #admin fakultas
                    "createdAt": datetime(2025, 11, 25, 9, 0)
                },
                "expected": "Lolos Greedy, menunggu verifikasi Admin"
            }
        ]

    def test_peminjaman(self):
        for test in self.test_cases:
            with self.subTest(test["name"]):
                result = prosesPeminjamanBaru(test["request"], self.existing)
                self.assertEqual(result, test["expected"])


if __name__ == "__main__":
    unittest.main()

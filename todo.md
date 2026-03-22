Fixed: Changed all include_once './db/config.php' or '../db/config.php' to __DIR__ . '/db/config.php' or __DIR__ . '/../db/config.php' for consistent path resolution.

Fixed: Used prepared statements for SQL queries with variables.


Created missing files: recordAttendance.php, addSubject.php, addUser.php for AJAX functionality.

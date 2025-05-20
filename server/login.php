<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $user_type = $_POST['user_type'] ?? 'student'; // 'student' or 'faculty'

    if (empty($identifier) || empty($password)) {
        $error = "Invalid login credentials.";
    } else {
        $conn = db_connect();

        if ($user_type === 'faculty') {
            $stmt = $conn->prepare(
                "SELECT faculty_id, nu_email, first_name, last_name, password FROM faculty WHERE faculty_id = ? OR nu_email = ? LIMIT 1"
            );
        } else {
            $stmt = $conn->prepare(
                "SELECT student_id, nu_email, first_name, last_name, password FROM students WHERE student_id = ? OR nu_email = ? LIMIT 1"
            );
        }
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                session_regenerate_id(true);
                if ($user_type === 'faculty') {
                    $_SESSION['faculty_id'] = $row['faculty_id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    $_SESSION['user_type'] = 'faculty';
                    header("Location: faculty_portal.php");
                } else {
                    $_SESSION['student_id'] = $row['student_id'];
                    $_SESSION['first_name'] = $row['first_name'];
                    $_SESSION['last_name'] = $row['last_name'];
                    $_SESSION['user_type'] = 'student';
                    header("Location: dashboard.php");
                }
                exit;
            }
        }
        $error = "Invalid login credentials.";
        $stmt->close();
        $conn->close();
    }
}
?>

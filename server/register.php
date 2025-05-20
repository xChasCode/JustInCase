<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $student_id      = trim($_POST['student_id'] ?? '');
    $nu_email        = trim($_POST['nu_email'] ?? '');
    $first_name      = trim($_POST['first_name'] ?? '');
    $last_name       = trim($_POST['last_name'] ?? '');
    $middle_initial  = trim($_POST['middle_initial'] ?? '');
    $year_level      = trim($_POST['year_level'] ?? '');
    $contact_num     = trim($_POST['contact_num'] ?? '');
    $program         = trim($_POST['program'] ?? '');
    $password        = $_POST['password'] ?? '';
    $confirm         = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (
        empty($student_id) || empty($nu_email) || empty($first_name) || empty($last_name) ||
        empty($year_level) || empty($contact_num) || empty($program) ||
        empty($password) || empty($confirm)
    ) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $conn = db_connect();

        // Check for duplicate student_id or email
        $stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ? OR nu_email = ? LIMIT 1");
        $stmt->bind_param("ss", $student_id, $nu_email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Student ID or NU Email already registered.";
        } else {
            // Hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Insert new student
            $stmt = $conn->prepare(
                "INSERT INTO students (student_id, nu_email, first_name, last_name, middle_initial, year_level, contact_num, program, password) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                "sssssssss",
                $student_id, $nu_email, $first_name, $last_name, $middle_initial,
                $year_level, $contact_num, $program, $hashed
            );
            if ($stmt->execute()) {
                $_SESSION['student_id'] = $student_id;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
        $stmt->close();
        $conn->close();
    }
}
?>

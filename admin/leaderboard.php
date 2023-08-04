<?php

include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_home.php');
}

include '../components/admin_header.php';

// Calculate previous month
$previousMonth = date('Y-m', strtotime('first day of previous month'));

// Fetch users and their data
$select_users = $conn->prepare('SELECT users.user_id, users.username,
                                (SELECT SUM(score) FROM score_activity WHERE score_activity.Users_user_id = users.user_id) AS total_score,
                                (SELECT SUM(tokens) FROM user_tokens WHERE user_tokens.Users_user_id = users.user_id AND user_tokens.date <= :previous_month) AS total_tokens_prev_month,
                                (SELECT SUM(tokens) FROM user_tokens WHERE user_tokens.Users_user_id = users.user_id) AS total_tokens
                                FROM users
                                WHERE users.user_type = "user"
                                ORDER BY total_score DESC');
$select_users->execute(['previous_month' => $previousMonth]);
$users = $select_users->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="ranking-list">
    <h2>Ranking List</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Total Score</th>
                <th>Total Tokens Previous Month</th>
                <th>Total Tokens</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $index => $user) { ?>
                <tr>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['total_score']; ?></td>
                    <td><?php echo $user['total_tokens_prev_month']; ?></td>
                    <td><?php echo $user['total_tokens']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<style>
.ranking-list {
    margin: 20px;
}

.ranking-list h2 {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
}

.ranking-list table {
    width: 100%;
    border-collapse: collapse;
}

.ranking-list th,
.ranking-list td {
    padding: 8px;
    text-align: center;
    border: 1px solid #ccc;
}

.ranking-list th {
    background-color: #f2f2f2;
}
</style>

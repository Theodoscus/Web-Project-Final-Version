<?php
include '../components/connect.php';

session_start();

$admin_product_id = $_SESSION['user_id'];

if (!isset($admin_product_id)) {
    header('location:admin_home.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>
    <?php include '../components/admin_header.php'; ?>

    <section class="section">
        <?php

        // Calculate previous month with day portion
        $previousMonth = date('Y-m-d', strtotime('first day of previous month'));

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

        // Pagination settings
        $itemsPerPage = 10;
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // Calculate total pages and start/end indices
        $totalItems = count($users);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $endIndex = min($startIndex + $itemsPerPage, $totalItems);
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
                    <?php for ($i = $startIndex; $i < $endIndex; ++$i) { ?>
                        <tr>
                            <td><?php echo $users[$i]['username']; ?></td>
                            <td><?php echo $users[$i]['total_score']; ?></td>
                            <td><?php echo $users[$i]['total_tokens_prev_month']; ?></td>
                            <td><?php echo $users[$i]['total_tokens']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php
            // Calculate the range of pages to display
            $chunkSize = 10; // Number of page links to display at once
            $startPage = max($currentPage - floor($chunkSize / 2), 1);
            $endPage = min($startPage + $chunkSize - 1, $totalPages);

            // Display previous page link
            if ($currentPage > 1) {
                echo "<a href='?page=" . ($currentPage - 1) . "' class='page-link'>&laquo;</a>";
            }

            for ($page = $startPage; $page <= $endPage; ++$page) {
                $activeClass = ($page === $currentPage) ? 'active' : '';

                // Construct the correct URL by encoding the search term and page number
                $url = "?page=$page";

                // Display the page links within the calculated range
                echo "<a href='$url' class='page-link $activeClass'>$page</a>";
            }

            // Display next page link
            if ($currentPage < $totalPages) {
                echo "<a href='?page=" . ($currentPage + 1) . "' class='page-link'>&raquo;</a>";
            }
            ?>
        </div>
    </section>
</body>

</html>
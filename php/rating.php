<?php /* A000001028001001/ws/php/rating.php */

function get_rating($config)
{
    $ratingFile = 'ratingData';
    if (file_exists($ratingFile)) {

    }

    $db = $config['components']['db'];

    $servername = "localhost";
    $hostname   = 'localhost';
    $username   = $db["username"];
    $password   = $db["password"];
    $dsn        = $db["dsn"];
    $a          = explode('=', $dsn);
    $database   = $a[array_key_last($a)];

    $conn = new mysqli("$hostname", "$username", "$password", "$database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql    = ' SELECT rate_review.email, rate_review.rating, rate_review.product_id, rate_product.page_path, rate_review.brief FROM rate_review INNER JOIN rate_product ON rate_review.product_id = rate_product.id;';
    $result = mysqli_query($conn, $sql);

    $summary = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $page_path = $row['page_path'];
            if (! array_key_exists($page_path, $summary)) {
                $summary[$page_path] = [1, $row['rating'], $row['brief']];
            } else {
                $summary[$page_path][0] += 1;
                $summary[$page_path][1] += $row['rating'];
            }

        }
    }

    mysqli_close($conn);
    $sumdata = "<script> window.asoneRatingSummary='" . base64_encode(json_encode($summary)) . "';</script>";
//file_put_contents($ratingFile, $sumdata);
    return ($sumdata);

}
<?php

// require 'functions.php';
// require './controllers/articles.php';

function commandMail($user, $articles, $date, $address, $number)
{
    $text = '<!DOCTYPE html>

    <html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
    <title></title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/><!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]--><!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900" rel="stylesheet" type="text/css"/><!--<![endif]-->
    <style>
    table {
        text-align: center
        margin-top: 20px;
        margin-bottom: 20px;
    }
    td {
        margin-right: 5px;
    }
    </style>
    <h1>Command Shipped<h1>';
    $text .= '<h2> Dear ' . $user['name'] . '</h2> <p> Your command is on its way. Here is a little <b>summary</b>';
    $text .= '<table>';
    $text .= '<table>';
    $text .= '<thead><tr><td>Article name</td> <td>Price</td> <td>Quantity</td> <td>Total</td></tr><thead>';
    $text .= '<tbody>';
    $total = 0;
    foreach ($articles as $at) {
        // $articleId = $article['articleId'];
        $conn = connect();
        $quantity = $at['quantity'];
        $article = getArticleById($conn, $at['articleId']);
        // $id = $article['id'];

        $text .= '<tr>' . '<td>' . $article['name'] . '</td>' . '<td>' . $article['price'] . ' €</td>' . '<td>' . $quantity . '</td>' . '<td>' . $article['price'] * $quantity . ' €</td>' . '</tr>';
        $total += $article['price'] * $quantity;
    }
    $text .= '</tbody><h3>Votre total est de  ' . $total . ' €</h3>';
    $text .= '</table>';
    $text .= '<div>';
    $text .= '<h4>delivery date ' . $date . '</h4>';
    $text .= '<h4>delivery Address ' . $address . '</h4>';
    $text .= '<h4>Track number ' . $number . '</h4>';
    $text .= '</div>';
    return $text;
}

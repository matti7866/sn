<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Letter Document</title>
  <style>
    body {
      font-family: 'Times New Roman', serif;
      max-width: 8.5in;
      margin: 0 auto;
      padding: 1in;
      background-color: #f0f0f0;
    }

    .letter-container {
      background-color: white;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border: 1px solid #ccc;
      min-height: 11in;
      padding: 0px;
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    @media print {
      body {
        background-color: white;
        padding: 0;
      }

      .letter-container {
        box-shadow: none;
        border: none;
        padding: 0;
      }
    }
  </style>
</head>

<body>
  <?php
  $token = $_GET['token'];
  $letter = null;
  if (file_exists(dirname(__FILE__) . '/assets/' . $token . '.png')) {
    $letter = 'assets/' . $token . '.png?v=' . time();
  }
  if ($letter) {
  ?>

    <div class="letter-container">
      <img src="<?= $letter ?>" alt="Letter" />
    </div>
  <?php } ?>
</body>

</html>
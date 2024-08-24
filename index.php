<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Calculator with Database</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .calculator {
            max-width: 300px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, opacity 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838;
            opacity: 0.9;
        }
        .result {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="calculator">
    <form method="POST" action="">
        <input type="number" name="number1" placeholder="Enter First Number" required>
        <input type="number" name="number2" placeholder="Enter Second Number" required>

        <select name="operation">
            <option value="add">Add</option>
            <option value="subtract">Subtract</option>
            <option value="multiply">Multiply</option>
            <option value="division">Division</option>
        </select>

        <input type="submit" name="submit" value="Calculate">
    </form>

    <div class="result">
        <?php

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "calculator1_db";

        $conn = mysqli_connect($servername, $username, $password, $database);

        if (!$conn) {
            die("Sorry, we failed to connect: " . mysqli_connect_error());
        }

        if (isset($_POST["submit"])) {
            $number1 = $_POST["number1"];
            $number2 = $_POST["number2"];
            $operation = $_POST["operation"];
            $result = 0;

            if ($operation == "add") {
                $result = $number1 + $number2;
            } elseif ($operation == "subtract") {
                $result = $number1 - $number2;
            } elseif ($operation == "multiply") {
                $result = $number1 * $number2;
            } elseif ($operation == "division") {
                if ($number2 != 0) {
                    $result = $number1 / $number2;
                } else {
                    echo "Cannot Divide by Zero!";
                    $result = null;
                }
            }

            if ($result !== null) {
                $stmt = $conn->prepare("INSERT INTO calculations1 (number1, number2, operation, result) VALUES (?, ?, ?, ?)");
                
                if ($stmt === false) {
                    die("Error preparing statement: " . $conn->error);
                }
              
                $stmt->bind_param("ddsd", $number1, $number2, $operation, $result);
                $stmt->execute();
                $stmt->close();

                echo "Your Result: $result";
            }
        }
        mysqli_close($conn);
        ?>
    </div>
</div>

</body>
</html>

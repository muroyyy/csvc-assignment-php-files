<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Query Page</title>
    <link rel="stylesheet" href="css/styles.css"> 
</head>
<body>
    <h1>Example Social Research Organization</h1>
    <h2>Country Data Query Page</h2>
    <p><a href="index.php">Home</a></p>

    <h3>Please select which query you want to run:</h3>
    <form method="post" action="query2.php">
        <select name="selection" required>
            <option value="">-- Select a query --</option>
            <option value="Q1">Mobile Phones</option>
            <option value="Q2">Population</option>
            <option value="Q3">Life Expectancy</option>
            <option value="Q4">GDP</option>
            <option value="Q5">Childhood Mortality</option>
        </select>
        <input type="submit" value="Submit">
    </form>
</body>
</html>

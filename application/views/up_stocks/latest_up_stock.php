<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
</head>
<body>
    <h1><?php echo $title; ?></h1>

    <?php if (!empty($stocks)): ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Close Price</th>
                    <th>% Change</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?php echo $stock['company_symbol']; ?></td>
                        <td><?php echo $stock['company_name']; ?></td>
                        <td><?php echo $stock['close_price']; ?></td>
                        <td><?php echo $stock['price_change_in_p']; ?>%</td>
                        <td><?php echo $stock['stock_date']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No stocks found with that much rise today.</p>
    <?php endif; ?>
</body>
</html>

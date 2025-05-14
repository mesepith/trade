<div class="container">
    <h1><?php echo $title; ?></h1>

    <?php if (!empty($stocks)): ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>Symbol</th>
                    <th>Name</th>
                    <th>Total % Change</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $stock): ?>
                    <tr>
                        <td><?php echo $stock['company_symbol']; ?></td>
                        <td><?php echo $stock['company_name']; ?></td>
                        <td><?php echo round($stock['total_change'], 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No stocks found matching the criteria.</p>
    <?php endif; ?>
</div>

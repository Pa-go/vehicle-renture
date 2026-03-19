<?php
include '../database/db_config.php';
session_start();

// Security: Only allow logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: log_reg.php");
    exit();
}

$owner_id = $_SESSION['user_id'];

// SQL: Join Bookings with Users (to get Buyer info) and Vehicles (to filter by Owner)
$sql = "SELECT 
            b.id as booking_id,
            u.name as buyer_name,
            u.email as buyer_email,
            u.contact as buyer_phone,
            v.vehicle_name,
            b.amount,
            b.payment_method,
            b.booking_date
        FROM bookings b
        JOIN users u ON b.tenant_id = u.id
        JOIN vehicles v ON b.vehicle_id = v.id
        WHERE v.owner_id = ? 
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lender Dashboard | Renture</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .dashboard-container { max-width: 1000px; margin: 50px auto; padding: 20px; font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #001f3f; color: white; }
        tr:hover { background-color: #f9f9f9; }
        .status-paid { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="dashboard-container">
        <h1>My Vehicle Bookings</h1>
        <p>Manage your rentals and see who is interested in your vehicles.</p>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Buyer Name</th>
                    <th>Vehicle</th>
                    <th>Amount Paid</th>
                    <th>Contact Info</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bookings->num_rows > 0): ?>
                    <?php while($row = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($row['booking_date'])); ?></td>
                            <td><strong><?php echo $row['buyer_name']; ?></strong></td>
                            <td><?php echo $row['vehicle_name']; ?></td>
                            <td class="status-paid">₹<?php echo number_format($row['amount'], 2); ?></td>
                            <td>
                                📞 <?php echo $row['buyer_phone']; ?><br>
                                ✉️ <?php echo $row['buyer_email']; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center;">No bookings found yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
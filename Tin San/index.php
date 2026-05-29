<?php
// ==========================================
// ၁။ DATABASE ချိတ်ဆက်ခြင်းနှင့် တည်ဆောက်ခြင်း
// ==========================================
$conn = new mysqli("localhost", "root", "");

// Database မရှိသေးရင် အလိုအလျောက် ဆောက်ပေးမည်
$conn->query("CREATE DATABASE IF NOT EXISTS simple_lucky_db");
$conn->select_db("simple_lucky_db");

// Table မရှိသေးရင် ဆောက်ပေးမည်
$conn->query("CREATE TABLE IF NOT EXISTS bets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bet_type VARCHAR(5) NOT NULL,
    bet_number VARCHAR(5) NOT NULL,
    amount INT NOT NULL,
    bet_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ==========================================
// ၂။ ခလုတ်နှိပ်လိုက်ရင် ဒေတာသိမ်းမည့်စနစ်
// ==========================================
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bet_type = $_POST['bet_type'];
    $bet_number = $_POST['bet_number'];
    $amount = $_POST['amount'];

    // ဒေတာလှမ်းသိမ်းခြင်း
    $stmt = $conn->prepare("INSERT INTO bets (bet_type, bet_number, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $bet_type, $bet_number, $amount);
    
    if ($stmt->execute()) {
        $message = "<p style='color: green; font-weight: bold;'>✅ စာရင်းသွင်းပြီးပါပြီ။</p>";
    } else {
        $message = "<p style='color: red;'>❌ အမှားအယွင်းရှိပါသည်။</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lucky 2D/3D</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; text-align: center; padding: 30px; }
        .box { background: white; max-width: 400px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, select { width: 90%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
        button { width: 95%; padding: 12px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>

<div class="box">
    <h2>2D / 3D Lucky Draw</h2>
    
    <!-- အောင်မြင်မှု သတင်းစကားပြရန် -->
    <?php echo $message; ?>

    <!-- ဖြည့်ရမည့် ဖောင် -->
    <form method="POST">
        <select name="bet_type">
            <option value="2D">2D (ဂဏန်း ၂ လုံး)</option>
            <option value="3D">3D (ဂဏန်း ၃ လုံး)</option>
        </select>
        <input type="text" name="bet_number" placeholder="ထိုးမည့်ဂဏန်း ရေးပါ" required>
        <input type="number" name="amount" placeholder="ထိုးကြေး (ကျပ်)" required>
        <button type="submit">စာရင်းသွင်းမည်</button>
    </form>

    <<hr style="margin-top:20px;">

    <!-- ထိုးထားသောစာရင်း ပြန်ပြရန် -->
    <h3>လက်ရှိထိုးထားသော မှတ်တမ်း</h3>
    <table>
        <tr>
            <th>အမျိုးအစား</th>
            <th>ဂဏန်း</th>
            <th>ပမာဏ</th>
        </tr>
        <?php
        $result = $conn->query("SELECT * FROM bets ORDER BY id DESC LIMIT 5");
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['bet_type']}</td>
                    <td>{$row['bet_number']}</td>
                    <td>{$row['amount']} Ks</td>
                  </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

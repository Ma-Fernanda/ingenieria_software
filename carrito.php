<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: iniciar_sesion.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Carrito de Compras | El Rincón Panadero</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/estiloscss.css" />
</head>
<body>
  <div class="container">
    <h1 class="mt-4">Carrito de Compras</h1>

    <?php if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
      <section class="carrito p-3 border rounded bg-light my-4">
        <h2>Productos en tu Carrito</h2>
        <table class="table table-bordered text-center">
          <thead class="table-light">
            <tr>
              <th>Producto</th>
              <th>Precio Unitario</th>
              <th>Cantidad</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $total = 0;
              foreach ($_SESSION['carrito'] as $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
              <tr>
                <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                <td>$<?php echo number_format($item['precio'], 2); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="text-end fw-bold fs-5">Total: $<?php echo number_format($total, 2); ?></div>

        <div class="text-end mt-3">
          <form action="vaciar_carrito.php" method="POST">
            <button class="btn btn-danger">Vaciar Carrito</button>
          </form>
        </div>
      </section>
    <?php else: ?>
      <div class="alert alert-info mt-4">Tu carrito está vacío.</div>
    <?php endif; ?>
  </div>
</body>
</html>

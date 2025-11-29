<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>División de Plantas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px;
        }
        .container {
            width: 480px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input[type="number"]{
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }
        button{
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover{ background: #0056b3; }
        .resultado{
            margin-top: 20px;
            padding: 15px;
            background: #e9f5e9;
            border: 1px solid #b5dfb5;
            border-radius: 8px;
        }
        .info{
            margin-top: 12px;
            padding: 12px;
            background: #eef3ff;
            border: 1px solid #b7c5ff;
            border-radius: 8px;
        }
        .error{
            margin-top: 20px;
            padding: 15px;
            background: #ffe5e5;
            border: 1px solid #ff9999;
            border-radius: 8px;
        }
        em.note { color: #007bff; display:block; margin-top:8px; }
    </style>
</head>
<body>
<div class="container">
    <h2>División de Plantas entre Dos Personas</h2>

    <form method="POST">
        <label>Total de Plantas:</label>
        <input type="number" name="total" min="1" required />

        <label>Diferencia Permitida:</label>
        <input type="number" name="diff" min="0" required />

        <button type="submit">Calcular</button>
    </form>

    <div class="info">
        <strong>Cuadro de referencia:</strong>
        <p>• Si el <strong>total es par</strong>, la diferencia debe ser <strong>par</strong>.</p>
        <p>• Si el <strong>total es impar</strong>, la diferencia debe ser <strong>impar</strong>.</p>
        <p>• Si no coincide la paridad, el sistema intentará ajustar la diferencia automáticamente en ±1 y te mostrará el cambio.</p>
    </div>

    <?php
    if ($_POST) {
        $total = intval($_POST['total']);
        $diff = intval($_POST['diff']);
        $orig_diff = $diff; // guardamos lo que ingresó el usuario
        $ajuste_msg = '';

        // validación básica
        if ($diff > $total) {
            echo "<div class='error'>La diferencia no puede ser mayor al total.</div>";
        } else {
            // Si la paridad no coincide, intentamos ajustar -1 o +1
            if (($total % 2) !== ($diff % 2)) {
                $ajustado = false;

                // probar diff - 1
                if ($diff - 1 >= 0 && (($total + ($diff - 1)) % 2 === 0)) {
                    $diff = $diff - 1;
                    $ajuste_msg = "Ingresaste <strong>{$orig_diff}</strong>; se restó 1 porque la paridad no coincidía. Diferencia usada: <strong>{$diff}</strong>.";
                    $ajustado = true;
                }

                // si no se ajustó con -1, probar +1
                if (!$ajustado && (($total + ($diff + 1)) % 2 === 0)) {
                    $diff = $diff + 1;
                    $ajuste_msg = "Ingresaste <strong>{$orig_diff}</strong>; se sumó 1 porque la paridad no coincidía. Diferencia usada: <strong>{$diff}</strong>.";
                    $ajustado = true;
                }

                // si no se pudo ajustar, mostrar error
                if (!$ajustado) {
                    echo "<div class='error'>No fue posible ajustar la diferencia en ±1 para que coincida la paridad. Intenta con otro número.</div>";
                    // salir para no continuar con cálculos inválidos
                    return;
                }
            }

            // ahora calculamos usando intdiv para obtener enteros
            $p1 = intdiv($total + $diff, 2); // persona1
            $p2 = $total - $p1;              // persona2 (asegura suma == total)

            // comprobaciones finales
            if ($p1 >= 0 && $p2 >= 0) {
                echo "<div class='resultado'>
                        <h3>Resultado</h3>
                        <p><strong>Persona 1:</strong> {$p1} plantas</p>
                        <p><strong>Persona 2:</strong> {$p2} plantas</p>
                        <p><strong>Diferencia usada:</strong> {$diff} plantas</p>";
                if (!empty($ajuste_msg)) {
                    echo "<p class='note'><em>{$ajuste_msg}</em></p>";
                } else {
                    echo "<p class='note'><em>Usaste exactamente la diferencia ingresada: <strong>{$orig_diff}</strong>.</em></p>";
                }
                echo "<p><strong>Total:</strong> " . ($p1 + $p2) . " plantas</p>
                      </div>";
            } else {
                echo "<div class='error'>Error en el reparto. Revisa los números e intenta de nuevo.</div>";
            }
        }
    }
    ?>
</div>
</body>
</html>


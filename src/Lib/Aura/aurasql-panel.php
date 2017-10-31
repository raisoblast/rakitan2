<?php
namespace Rakitan\Lib\Aura;

?>
<h1>AuraSql [<?= $this->connectionName ?>]</h1>
<div class="tracy-inner">
<table>
<tr>
    <th>Time (ms)</th>
    <th>Function</th>
    <th>SQL</th>
    <th>Bind Values</th>
</tr>
<?php foreach ($messages as $message): ?>
<tr>
    <td><?= round($message['time'], 2) ?></td>
    <td><?= $message['function'] ?></td>
    <td><?= $message['sql'] ?></td>
    <td><?= $message['params'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
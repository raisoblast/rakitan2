<?php
namespace Rakitan\Lib\Propel;

?>
<h1>Propel2</h1>
<div class="tracy-inner">
<table>
<tr>
    <th>Time (ms)</th>
    <th>SQL</th>
    <th>Memory</th>
    <th>Memory Delta</th>
    <th>Memory Peak</th>
</tr>
<?php foreach ($this->queries as $query): ?>
<tr>
    <td><?= $query['time'] ?></td>
    <td><?= $query['query'] ?></td>
    <td><?= $query['memory'] ?></td>
    <td><?= $query['memoryDelta'] ?></td>
    <td><?= $query['memoryPeak'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
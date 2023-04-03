<div class="container mt-4">
    <h1>Dashboard</h1>
    <?php if ($topics): ?>
    <div class="row">
        <div class="col-md-6 pt-4">
            <h2>Tópicos asignados</h2>
            <ul>
                <?php foreach ($topics as $topic): ?>
                <li><a
                        href="<?php echo generate_link('topics_show', array('id' => $topic['id'])); ?>"><?php echo $topic['name']; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php if ($reports): ?>
        <div class="col-md-6">
            <h2>Reportes</h2>
            <ul>
                <?php foreach ($reports as $report): ?>
                <li><a
                        href="<?php echo generate_link('reports_show', array('id' => $report['id'])); ?>"><?php echo $report['name']; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>

    <?php if ($charts): ?>
    <div class="row">
        <div class="col-md-12">
            <h2>Gráficos</h2>
            <ul>
                <?php foreach ($charts as $chart): ?>
                <li><a
                        href="<?php echo generate_link('charts_show', array('id' => $chart['id'])); ?>"><?php echo $chart['name']; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <?php else: ?>
    <p>Actualmente no tienes tópicos asignados.</p>
    <?php endif; ?>
</div>
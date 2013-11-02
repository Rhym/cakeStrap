<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>

<div id="page-container" class="row">

	<div id="sidebar" class="col-sm-3">
		
		<div class="actions">
			
			<ul class="list-group">			
				<?php
					echo "\t\t<li class=\"list-group-item\"><?php echo \$this->Html->link(__('Edit " . $singularHumanName ."'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => '')); ?> </li>\n";
					echo "\t\t<li class=\"list-group-item\"><?php echo \$this->Form->postLink(__('Delete " . $singularHumanName . "'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => ''), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?> </li>\n";
					echo "\t\t<li class=\"list-group-item\"><?php echo \$this->Html->link(__('List " . $pluralHumanName . "'), array('action' => 'index'), array('class' => '')); ?> </li>\n";
					echo "\t\t<li class=\"list-group-item\"><?php echo \$this->Html->link(__('New " . $singularHumanName . "'), array('action' => 'add'), array('class' => '')); ?> </li>\n";

					$done = array();
					foreach ($associations as $type => $data) {
						foreach ($data as $alias => $details) {
							if ($details['controller'] != $this->name && !in_array($details['controller'], $done)) {
								echo "\t\t<li class=\"list-group-item\"><?php echo \$this->Html->link(__('List " . Inflector::humanize($details['controller']) . "'), array('controller' => '{$details['controller']}', 'action' => 'index'), array('class' => '')); ?> </li>\n";
								echo "\t\t<li class=\"list-group-item\"><?php echo \$this->Html->link(__('New " .  Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add'), array('class' => '')); ?> </li>\n";
								$done[] = $details['controller'];
							}
						}
					}
				?>				
			</ul><!-- /.list-group -->
			
		</div><!-- /.actions -->
		
	</div><!-- /#sidebar .span3 -->
	
	<div id="page-content" class="col-sm-9">
		
		<div class="<?php echo $pluralVar; ?> view">

			<h2><?php echo "<?php  echo __('{$singularHumanName}'); ?>"; ?></h2>
			
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<tbody>
						<?php
						foreach ($fields as $field) {
							$isKey = false;
							if (!empty($associations['belongsTo'])) {
								foreach ($associations['belongsTo'] as $alias => $details) {
									if ($field === $details['foreignKey']) {
										$isKey = true;
										echo "<tr>";
										echo "\t\t<td><strong><?php echo __('" . Inflector::humanize(Inflector::underscore($alias)) . "'); ?></strong></td>\n";
										echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), array('class' => '')); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
										echo "</tr>";
										break;
									}
								}
							}
							if ($isKey !== true) {
								echo "<tr>";
								echo "\t\t<td><strong><?php echo __('" . Inflector::humanize($field) . "'); ?></strong></td>\n";
								echo "\t\t<td>\n\t\t\t<?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>\n\t\t\t&nbsp;\n\t\t</td>\n";
								echo "</tr>";
							}
						}
						?>
					</tbody>
				</table><!-- /.table table-striped table-bordered -->
			</div><!-- /.table-responsive -->
			
		</div><!-- /.view -->

		<?php
		if (!empty($associations['hasOne'])) :
			foreach ($associations['hasOne'] as $alias => $details): ?>
				<div class="related">
					<h3><?php echo "<?php echo __('Related " . Inflector::humanize($details['controller']) . "'); ?>"; ?></h3>
					<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
						<table class="table table-striped table-bordered">
							<?php
							foreach ($details['fields'] as $field) {
								echo "<tr>";
								echo "\t\t<td><strong><?php echo __('" . Inflector::humanize($field) . "'); ?></strong></td>\n";
								echo "\t\t<td><strong><?php echo \${$singularVar}['{$alias}']['{$field}']; ?>\n&nbsp;</strong></td>\n";
								echo "</tr>";
							}
							?>
						</table><!-- /.table table-striped table-bordered -->
					<?php echo "<?php endif; ?>\n"; ?>
					<div class="actions">
						<?php echo "<li><?php echo \$this->Html->link(__('<i class=\"icon-pencil icon-white\"></i> Edit " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$singularVar}['{$alias}']['{$details['primaryKey']}']), array('class' => 'btn btn-primary', 'escape' => false)); ?>\n"; ?>
					</div><!-- /.actions -->
				</div><!-- /.related -->
			<?php
			endforeach;
		endif;

		if (empty($associations['hasMany'])) {
			$associations['hasMany'] = array();
		}
		if (empty($associations['hasAndBelongsToMany'])) {
			$associations['hasAndBelongsToMany'] = array();
		}
		$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
		$i = 0;
		foreach ($relations as $alias => $details):
			$otherSingularVar = Inflector::variable($alias);
			$otherPluralHumanName = Inflector::humanize($details['controller']);
			?>
			
			<div class="related">

				<h3><?php echo "<?php echo __('Related " . $otherPluralHumanName . "'); ?>"; ?></h3>
				
				<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
					
					<div class="table-responsive">
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<?php
										foreach ($details['fields'] as $field) {
											echo "\t\t<th><?php echo __('" . Inflector::humanize($field) . "'); ?></th>\n";
										}
									?>
									<th class="actions"><?php echo "<?php echo __('Actions'); ?>"; ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								echo "\t<?php
										\$i = 0;
										foreach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
										echo "\t\t<tr>\n";
											foreach ($details['fields'] as $field) {
												echo "\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
											}

											echo "\t\t\t<td class=\"actions\">\n";
											echo "\t\t\t\t<?php echo \$this->Html->link(__('View'), array('controller' => '{$details['controller']}', 'action' => 'view', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-default btn-xs')); ?>\n";
											echo "\t\t\t\t<?php echo \$this->Html->link(__('Edit'), array('controller' => '{$details['controller']}', 'action' => 'edit', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-default btn-xs')); ?>\n";
											echo "\t\t\t\t<?php echo \$this->Form->postLink(__('Delete'), array('controller' => '{$details['controller']}', 'action' => 'delete', \${$otherSingularVar}['{$details['primaryKey']}']), array('class' => 'btn btn-default btn-xs'), __('Are you sure you want to delete # %s?', \${$otherSingularVar}['{$details['primaryKey']}'])); ?>\n";
											echo "\t\t\t</td>\n";
										echo "\t\t</tr>\n";

								echo "\t<?php endforeach; ?>\n";
								?>
							</tbody>
						</table><!-- /.table table-striped table-bordered -->
					</div><!-- /.table-responsive -->
					
				<?php echo "<?php endif; ?>\n\n"; ?>
				
				<div class="actions">
					<?php echo "<?php echo \$this->Html->link('<i class=\"icon-plus icon-white\"></i> '.__('New " . Inflector::humanize(Inflector::underscore($alias)) . "'), array('controller' => '{$details['controller']}', 'action' => 'add'), array('class' => 'btn btn-primary', 'escape' => false)); ?>"; ?>
				</div><!-- /.actions -->
				
			</div><!-- /.related -->

		<?php endforeach; ?>
	
	</div><!-- /#page-content .span9 -->

</div><!-- /#page-container .row-fluid -->

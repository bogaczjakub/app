<div class="col-xs-12">
	<div class="panel panel-default form-panel">
		<div class="panel-heading">
			<div class="table-name">Modules list</div>
			<div class="window-buttons">
				<button type="button" class="btn btn-success panel-hide-toggle">
					<span class="glyphicon glyphicon-minus"></span>
				</button>
				<button type="button" class="btn btn-danger">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
		</div>
		<div class="panel-body">
			{if isset($template_data.modules_list) &&
			!empty($template_data.modules_list)} {foreach
			from=$template_data.modules_list item=$item key=$key name=module_list}
			<div
				id="{$item['name']}-module-panel"
				class="panel panel-default module-panel"
			>
				<div class="panel-body">
					<div class="media">
						<div class="media-left">
							{if isset($item['icon']) && !empty($item['icon'])}
							<img class="media-object" src="" alt="" />
							{else}
							<p class="media-object">
								<span
									class="glyphicon glyphicon-object-align-horizontal"
									aria-hidden="true"
								></span>
							</p>
							{/if}
						</div>
						<div class="media-body">
							<h4 class="module-heading">{ucfirst($item['name'])}</h4>
							<p class="module-description">{$item['description']}</p>
							<p class="module-category">category: {$item['category_name']}</p>
							{if $item['author'] != 'null'}
							<p class="module-author">author: {$item['author']}</p>
							{/if}
						</div>

						<div class="media-right">
							<div class="buttons-container">
								{if !empty($item['buttons']['settings']) ||
								!empty($item['buttons']['configuration'])}
								<div class="btn-group">
									<button
										id="{$item['name']}-module-action-button"
										type="button"
										class="btn btn-primary dropdown-toggle module-action-button"
										data-toggle="dropdown"
										aria-haspopup="true"
										aria-expanded="false"
									>
										<span class="glyphicon glyphicon-wrench"></span>
										Action <span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										{if !empty($item['buttons']['settings'])}
										<li><a href="{$item['buttons']['settings']}" target="_self">Settings</a></li>
										{/if} {if !empty($item['buttons']['configuration'])}
										<li><a href="{$item['buttons']['configuration']}" target="_self">Configuration</a></li>
										{/if}
									</ul>
								</div>
								{/if}
								<button type="button" class="btn btn-danger">
									<span class="glyphicon glyphicon-remove"></span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			{/foreach} {/if}
		</div>
	</div>
</div>

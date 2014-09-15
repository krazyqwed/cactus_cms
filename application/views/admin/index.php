<div class="row">
	<div class="col-md-4 index-desc">
		<div id="widget-features" class="widget">
			<div class="widget-header"><h1>Bevezető</h1></div>

			<p>
				A Cactus CMS egy Codeigniter 2.1.4-re épülő rendszer, mely az egyszerűbb oldalak fejlesztési idejét kívánja csökkenteni.
			</p>
			<ul class="list-group">
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Tartalom, menü és blokk kezelés
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Elrendezés kezelés
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Többnyelvűség
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Szép URL-ek
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Kép -és fájlkezelés
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Moduláris MVC (Blokkok)
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					SEO
				</li>
				<li class="list-group-item">
					<span class="pull-right"><i class="fa fa-heart"></i></span>
					Jogosultságkezelés
				</li>
			</ul>
		</div>
	</div>

	<div class="col-md-4">
		<div class="widget">
			<div class="widget-header"><h1>Látogatók</h1></div>
			
			Aktív felhasználók száma:
			<h1><?php echo c_session_active_number() ?></h1>
		</div>

		<div class="widget index-documentation">
			<div class="widget-header"><h1>Dokumentáció</h1></div>

			<p>
				<span class="fa fa-stack fa-5x">
					<i class="fa fa-book"></i>
					<i class="fa fa-ban fa-stack-2x"></i>
				</span>
			</p>
			<p>A dokumentáció készítés alatt áll...</p>
		</div>
	</div>

	<div class="col-md-4">
		<div class="widget">
			<div class="widget-header"><h1>Fejlesztési napló</h1></div>

			<div class="change-note-wrap">
				<pre class="change-note"><?php echo $change_note; ?></pre>
			</div>
		</div>
	</div>
</div>
<div class="row file-tree">
    <div class="modal fade file-save-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Figyelem!</h4>
          </div>
          <div class="modal-body">
            <p>Nem lettek elmentve a módosítások!</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" onclick="fileTreeLoadFile(file_tree_temp_path, true);">Váltás mentés nélkül</button>
            <button type="button" class="btn btn-primary save-file save-file-modal" data-dismiss="modal">Változtatások mentése</button>
          </div>
        </div>
      </div>
    </div>

    <div class="file-tree-left">
        <div id="page-header">
            <h1>Fájlkezelő</h1>
        </div>

        <?php echo $tree ?>
    </div>
    <div class="file-tree-right">
        <div class="menu">
            <div class="indicator"></div>

            <ul>
                <li class="save-file">Mentés</li>
            </ul>
        </div>

        <form method="post" action="<?php base_url('admin/file_tree/save'); ?>">
            <input type="hidden" name="file" />
            <textarea name="content" class="codemirror"></textarea>
        </form>
    </div>
</div>
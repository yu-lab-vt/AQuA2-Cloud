<form action="" method="post" class="pt-3">
    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
    <input type="hidden" name="group" value="1">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm bg-white" id="main-table">
            <thead class="thead-white">
            <tr>
					
                    <th style="width:3%" class="custom-checkbox-header">
                    </th>
	</div> 
                <th>Name</th>
                <th>Size</th>
                <th>Modified</th>
            </tr>
            </thead>
            <?php
            // link to parent folder
            if ($parent !== false) {
                ?>
                <tr>
                    <td class="nosort"></td>
                    <td class="border-0" data-sort><a href="?p=<?php echo urlencode($parent) ?>"><i class="fa fa-chevron-circle-left go-back"></i> [Parent Directory]</a></td>
                    <td class="border-0" data-order></td>
                    <td class="border-0" data-order></td>
                    <td class="border-0"></td>
					<td class="border-0"></td>
					<td class="border-0"></td>
                </tr>
                <?php
            }
            $ii = 3399;
            foreach ($folders as $f) {
                $is_link = is_link($path . '/' . $f);
                $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
                $modif_raw = filemtime($path . '/' . $f);
                $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                if ($calc_folder) {
                    $filesize_raw = fm_get_directorysize($path . '/' . $f);
                    $filesize = fm_get_filesize($filesize_raw);
                }
                else {
                    $filesize_raw = "";
                    $filesize = 'Folder';
                }
                ?>
                <tr>
                        <td class="custom-checkbox-td">
                        <div class="custom-control custom-checkbox">
                        </div>
                        </td>
                    <td data-sort=<?php echo fm_enc($f) ?>>
                        <div class="filename"><a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="<?php echo $img ?>"></i> <?php echo fm_enc($f) ?>
                            </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
                    </td>
                    <td data-order="a-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT);?>">
                        <?php echo $filesize; ?>
                    </td>
                    <td data-order="a-<?php echo $modif_raw;?>"><?php echo $modif ?></td>
                    </td>
                </tr>
                <?php
                flush();
                $ii++;
            }
            $ik = 6070;
            foreach ($files as $f) {
                $is_link = is_link($path . '/' . $f);
                $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
                $modif_raw = filemtime($path . '/' . $f);
                $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                $filesize_raw = fm_get_size($path . '/' . $f);
                $filesize = fm_get_filesize($filesize_raw);
                $all_files_size += $filesize_raw;
                ?>
                <tr>
                        <td class="custom-checkbox-td">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]" value="<?php echo fm_enc($f) ?>" onclick="checkbox_toggle()">
                            <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                        </div>
                    <td data-sort=<?php echo fm_enc($f) ?>>
                        <div class="filename">
                        <?php
                           if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                           <?php else: ?>
                                <a title="<?php echo $f ?>">
                            <?php endif; ?>
                                    <i class="<?php echo $img ?>"></i> <?php echo fm_enc($f) ?>
                                </a>
                                <?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?>
                        </div>
                    </td>
                    <td data-order="b-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>"><span title="<?php printf('%s bytes', $filesize_raw) ?>">
                        <?php echo $filesize; ?>
                        </span></td>
                    <td data-order="b-<?php echo $modif_raw;?>"><?php echo $modif ?></td>
                    </td>
                </tr>
                <?php
                flush();
                $ik++;
            }

            if (empty($folders) && empty($files)) {
                ?>
                <tfoot>
                    <tr>
						<td></td>
                        <td colspan="<?php '4' ?>"><em>This directory is empty</em></td>
                    </tr>
                </tfoot>
                <?php
            } else {
                ?>
                <tfoot>
                    <tr>
						<td class="gray"></td>
                        <td class="gray" colspan="<?php echo '4' ?>">
                            <?php echo 'Directory total size of user files/folders'.': <span class="badge badge-light">'.fm_get_filesize($all_files_size).'</span>' ?>
                            <?php echo '| Directory no. of file(s)'.': <span class="badge badge-light">'.$num_files.'</span>' ?>
                            <?php echo '| Directory no. of folder(s)'.': <span class="badge badge-light">'.$num_folders.'</span>' ?>
                            <?php echo '| Service disk space'.': <span class="badge badge-light">'.fm_get_filesize(@disk_free_space($path)) .'</span> '.'free of'.' <span class="badge badge-light">'.fm_get_filesize(@disk_total_space($path)).'</span>'; ?>
							<?php echo '| Directory: ' ?>
							<a id="currentDirectory_HTML_JS_Handle"><?php echo $path ?></a>
						</td>
                    </tr>
                </tfoot>
                <?php
            }
            ?>
        </table>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-9">
            <ul class="list-inline footer-action">
				<li class="list-inline-item"><a href="#/unselect-all" class="btn btn-small btn-outline-primary btn-2" onclick="unselect_all();"><i class="fa fa-window-close"></i> Clear Selection</a></li>
            </ul>
        </div>
        <div class="col-3 d-none d-sm-block"><a" target="_blank" class="float-right text-muted">AQuA2-Cloud v1.0.1 [Release]</a></div>
    </div>
</form>
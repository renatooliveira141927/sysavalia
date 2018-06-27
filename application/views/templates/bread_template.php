<?php
/**
 * Created by PhpStorm.
 * User: Paulo Roberto
 * Date: 02/11/2017
 * Time: 15:22
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="row wrapper white-bg page-heading">
            <div class="col-lg-10">
                <h2><i class="fa fa-<?=$icon?>"></i> <?=$title_page?></h2>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?=base_url()?>">
                                <i class="fa fa-home" aria-hidden="true"></i>
                            </a>
                        </li>
                        <?php
                            $val = "";
                            foreach($list as $key){
                                $val .= "<li class=\"breadcrumb-item active\" aria-current=\"page\">$key</li>";
                            }
                            echo $val;
                        ?>
                    </ol>
                </nav>
            </div>
            <div class="col-lg-2">
                <div class="col-lg-offset-6 vcenter">
                <?php
                    if($btn_render) {
                        $icon_btn = "fa-plus-square";
                        $type = "Novo";
                        if ($btn_back) {
                            $icon_btn = "fa-reply";
                            $type = "Voltar";
                        }

                        $btn = "<button type=\"button\" class=\"btn btn-primary\"
                                     onclick=\"" . setLink(base_url($link)) . "\">
                                 <span class=\"fa " . $icon_btn . "\"></span> " . $type . "
                             </button>";

                        echo $btn;
                    }
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
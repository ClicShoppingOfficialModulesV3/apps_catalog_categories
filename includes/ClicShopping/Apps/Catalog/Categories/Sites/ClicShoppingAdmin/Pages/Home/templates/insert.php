<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\FileSystem;
  use ClicShopping\OM\HTTP;

  use ClicShopping\Sites\ClicShoppingAdmin\HTMLOverrideAdmin;

  use ClicShopping\Apps\Configuration\Administrators\Classes\ClicShoppingAdmin\AdministratorAdmin;

  $CLICSHOPPING_Categories = Registry::get('Categories');
  $CLICSHOPPING_Page = Registry::get('Site')->getPage();
  $CLICSHOPPING_Hooks = Registry::get('Hooks');
  $CLICSHOPPING_Template = Registry::get('TemplateAdmin');
  $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
  $CLICSHOPPING_CategoriesAdmin = Registry::get('CategoriesAdmin');
  $CLICSHOPPING_ProductsAdmin = Registry::get('ProductsAdmin');

  $CLICSHOPPING_Hooks->call('Categories', 'PreAction');

  // check if the catalog image directory exists
  if (is_dir($CLICSHOPPING_Template->getDirectoryPathTemplateShopImages())) {
    if (!FileSystem::isWritable($CLICSHOPPING_Template->getDirectoryPathTemplateShopImages())) $CLICSHOPPING_MessageStack->add($CLICSHOPPING_Categories->getDef('error_catalog_image_directory_not_writeable'), 'error');
  } else {
    $CLICSHOPPING_MessageStack->add($CLICSHOPPING_Categories->getDef('error_catalog_image_directory_does_not_exist'), 'error');
  }

  $languages = $CLICSHOPPING_Language->getLanguages();


  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } else {
    $cPath = '';
  }

  if (isset($_GET['cID'])) {
    $cID = $_GET['cID'];
  } else {
    $cID = '';
  }


  echo HTML::form('new_category', $CLICSHOPPING_Categories->link('Categories&Insert&cPath=' . $cPath . '&cID=' . $cID), 'post', 'enctype="multipart/form-data"');

  echo HTMLOverrideAdmin::getCkeditor();
?>

<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <span
            class="col-md-1 logoHeading"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/categorie.gif', $CLICSHOPPING_Categories->getDef('heading_title'), '40', '40'); ?></span>
          <span
            class="col-md-5 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_Categories->getDef('heading_title'); ?></span>
          <span class="col-md-6 text-md-right">
<?php
  echo HTML::hiddenField('categories_date_added', date('Y-m-d')) . HTML::button($CLICSHOPPING_Categories->getDef('button_update'), null, null, 'success') . ' ';
  echo HTML::button($CLICSHOPPING_Categories->getDef('button_cancel'), null, $CLICSHOPPING_Categories->link('Categories&cPath=' . $cPath . '&cID=' . $cID), 'warning');
?>
            </span>
        </div>
      </div>
    </div>
  </div>
  <div class="separator"></div>

  <div id="categoriesTabs" style="overflow: auto;">
    <ul class="nav nav-tabs flex-column flex-sm-row" role="tablist" id="myTab">
      <li
        class="nav-item"><?php echo '<a href="#tab1" role="tab" data-toggle="tab" class="nav-link active">' . $CLICSHOPPING_Categories->getDef('tab_general') . '</a>'; ?></li>
      <li
        class="nav-item"><?php echo '<a href="#tab2" role="tab" data-toggle="tab" class="nav-link">' . $CLICSHOPPING_Categories->getDef('tab_description') . '</a>'; ?></li>
      <li
        class="nav-item"><?php echo '<a href="#tab3" role="tab" data-toggle="tab" class="nav-link">' . $CLICSHOPPING_Categories->getDef('tab_ref') . '</a>'; ?></li>
      <li
        class="nav-item"><?php echo '<a href="#tab4" role="tab" data-toggle="tab" class="nav-link">' . $CLICSHOPPING_Categories->getDef('tab_img') . '</a>'; ?></li>
    </ul>
    <div class="tabsClicShopping">
      <div class="tab-content">
        <?php
          // -------------------------------------------------------------------
          //          ONGLET General sur la description de la categorie
          // -------------------------------------------------------------------
        ?>
        <div class="tab-pane active" id="tab1">
          <div class="col-md-12 mainTitle">
            <div class="float-md-left"><?php echo $CLICSHOPPING_Categories->getDef('text_products_name'); ?></div>
            <div
              class="float-md-right"><?php echo $CLICSHOPPING_Categories->getDef('text_user_name') . AdministratorAdmin::getUserAdmin(); ?></div>
          </div>
          <div class="adminformTitle" id="categoriesLanguage">
            <?php
              for ($i = 0, $n = count($languages); $i < $n; $i++) {
                ?>
                <div class="row">
                  <div class="col-md-5">
                    <div class="form-group row">
                      <label for="code"
                             class="col-2 col-form-label"><?php echo $CLICSHOPPING_Language->getImage($languages[$i]['code']); ?></label>
                      <div class="col-md-5">
                        <?php echo HTML::inputField('categories_name[' . $languages[$i]['id'] . ']', null, 'class="form-control" required aria-required="true" required="" id="categories_name" placeholder="' . $CLICSHOPPING_Categories->getDef('text_edit_categories_name') . '"', true) . '&nbsp;'; ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            ?>
            <div class="row" id="categoriesName">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_Categories->getDef('text_categories_name'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_Categories->getDef('text_categories_name'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::selectMenu('move_to_category_id', $CLICSHOPPING_CategoriesAdmin->getCategoryTree(), $cPath); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="separator"></div>
          <div class="col-md-12 mainTitle"><?php echo $CLICSHOPPING_Categories->getDef('text_divers_title'); ?></div>
          <div class="adminformTitle" id="categoriesSortOrder">
            <div class="row">
              <div class="col-md-5">
                <div class="form-group row">
                  <label for="<?php echo $CLICSHOPPING_Categories->getDef('text_edit_sort_order'); ?>"
                         class="col-5 col-form-label"><?php echo $CLICSHOPPING_Categories->getDef('text_edit_sort_order'); ?></label>
                  <div class="col-md-5">
                    <?php echo HTML::inputField('sort_order', null, 'size="2"'); ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php echo $CLICSHOPPING_Hooks->output('Categories', 'CategoriesContentTab1', null, 'display'); ?>
        </div>
        <?php
          // ----------------------------------------------------------- //-->
          //          categories description                              //-->
          // ----------------------------------------------------------- //-->
        ?>
        <div class="tab-pane" id="tab2">
          <div class="col-md-12 mainTitle">
            <span><?php echo $CLICSHOPPING_Categories->getDef('text_description_categories'); ?></span>
          </div>
          <div class="adminformTitle">
            <?php
              for ($i = 0, $n = count($languages); $i < $n; $i++) {
                ?>
                <div class="row">
                  <div class="col-md-1">
                    <div class="form-group row">
                      <label for="Code"
                             class="col-1 col-form-label"><?php echo $CLICSHOPPING_Language->getImage($languages[$i]['code']); ?></label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label for="lang" class="col-1 col-form-label"></label>
                      <div class="col-md-8">
                        <?php echo HTMLOverrideAdmin::textAreaCkeditor('categories_description[' . $languages[$i]['id'] . ']', 'soft', '750', '300', null); ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            ?>
          </div>
          <div class="separator"></div>
          <div class="alert alert-info" role="alert">
            <div><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/help.gif', $CLICSHOPPING_Categories->getDef('title_help_description')) . ' ' . $CLICSHOPPING_Categories->getDef('title_help_description') ?></div>
            <div class="separator"></div>
            <div class="row">
                <span class="col-md-12">
                 <?php echo $CLICSHOPPING_Categories->getDef('help_options'); ?>
                  <blockquote><i><a data-toggle="modal"
                                    data-target="#myModalWysiwyg2"><?php echo $CLICSHOPPING_Categories->getDef('text_help_wysiwyg'); ?></a></i></blockquote>
                 <div class="modal fade" id="myModalWysiwyg2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                      aria-hidden="true">
                   <div class="modal-dialog">
                     <div class="modal-content">
                       <div class="modal-header">
                         <button type="button" class="close" data-dismiss="modal"><span
                             aria-hidden="true">&times;</span></button>
                         <h4 class="modal-title"
                             id="myModalLabel"><?php echo $CLICSHOPPING_Categories->getDef('text_help_wysiwyg'); ?></h4>
                       </div>
                       <div class="modal-body text-md-center">
                         <img class="img-fluid"
                              src="<?php echo $CLICSHOPPING_Template->getImageDirectory() . '/wysiwyg.png'; ?>">
                       </div>
                     </div>
                   </div>
                 </div>
                </span>
            </div>
          </div>
          <?php echo $CLICSHOPPING_Hooks->output('Categories', 'CategoriesContentTab2', null, 'display'); ?>
        </div>
        <?php
          // -----------------------------------------------------//-->
      //          categories SEO      //-->
          // ---------------------------------------------------- //-->
        ?>
        <!-- decompte caracteres -->
        <script type="text/javascript">
            $(document).ready(function () {
              <?php
              for ($i = 0, $n = count($languages); $i < $n; $i++) {
              ?>
                //default title
                $("#default_title_<?php echo $i?>").charCount({
                    allowed: 70,
                    warning: 20,
                    counterText: ' Max : '
                });

                //default_description
                $("#default_description_<?php echo $i?>").charCount({
                    allowed: 150,
                    warning: 20,
                    counterText: 'Max : '
                });

                //default tag
                $("#default_tag_<?php echo $i?>").charCount({
                    allowed: 70,
                    warning: 20,
                    counterText: ' Max : '
                });
              <?php
              }
              ?>
            });
        </script>
        <div class="tab-pane" id="tab3">
          <div class="col-md-12 mainTitle">
            <span><?php echo $CLICSHOPPING_Categories->getDef('text_products_page_seo'); ?></span>
          </div>
          <div class="adminformTitle">
            <div class="spaceRow"></div>
            <div class="row">
              <div class="col-md-12 text-md-center">
                <span class="col-md-3"></span>
                <span class="col-md-3"><a href="https://www.google.fr/trends" target="_blank"
                                          rel="noreferrer"><?php echo $CLICSHOPPING_Categories->getDef('keywords_google_trend'); ?></a></span>

              </div>
            </div>
            <?php
              for ($i = 0, $n = count($languages); $i < $n; $i++) {
                ?>

                <div class="row">
                  <div class="col-md-1">
                    <div class="form-group row">
                      <label for="Code"
                             class="col-1 col-form-label"><?php echo $CLICSHOPPING_Language->getImage($languages[$i]['code']); ?></label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label for="<?php echo $CLICSHOPPING_Categories->getDef('text_products_page_title'); ?>"
                             class="col-1 col-form-label"><?php echo $CLICSHOPPING_Categories->getDef('text_products_page_title'); ?></label>
                      <div class="col-md-8">
                        <?php echo HTML::inputField('categories_head_title_tag[' . $languages[$i]['id'] . ']', null, 'maxlength="70" size="77" id="default_title_' . $i . '"', false); ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label for="<?php echo $CLICSHOPPING_Categories->getDef('text_products_page_title'); ?>"
                             class="col-1 col-form-label"><?php echo $CLICSHOPPING_Categories->getDef('text_products_header_description'); ?></label>
                      <div class="col-md-8">
                        <?php echo HTML::textAreaField('categories_head_desc_tag[' . $languages[$i]['id'] . ']', null, '75', '2', 'id="default_description_' . $i . '"'); ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label for="<?php echo $CLICSHOPPING_Categories->getDef('text_products_page_title'); ?>"
                             class="col-1 col-form-label"><?php echo $CLICSHOPPING_Categories->getDef('text_products_keywords'); ?></label>
                      <div class="col-md-8">
                        <?php echo HTML::textAreaField('categories_head_keywords_tag[' . $languages[$i]['id'] . ']', null, '75', '5'); ?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
              }
            ?>
            <div class="separator"></div>
            <div class="alert alert-info" role="alert">
              <div><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/help.gif', $CLICSHOPPING_Categories->getDef('title_help_submit')) . ' ' . $CLICSHOPPING_Categories->getDef('title_help_submit') ?></div>
              <div class="separator"></div>
              <div><?php echo $CLICSHOPPING_Categories->getDef('help_submit'); ?></div>
            </div>
          </div>
          <?php echo $CLICSHOPPING_Hooks->output('Categories', 'CategoriesContentTab3', null, 'display'); ?>
        </div>
        <?php
          // -----------------------------------------------------//-->
          //          ONGLET sur l'image de la categorie          //-->
          // ---------------------------------------------------- //-->
        ?>
        <div class="tab-pane" id="tab4">
          <div class="mainTitle">
            <span><?php echo $CLICSHOPPING_Categories->getDef('text_categories_image_title'); ?></span>
          </div>
          <div class="adminformTitle">
            <div class="row">
              <div class="col-md-12">
                <span
                  class="col-md-1"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/banner_manager.gif', $CLICSHOPPING_Categories->getDef('text_categories_image_vignette'), '40', '40'); ?></span>
                <span
                  class="col-md-3 main"><?php echo $CLICSHOPPING_Categories->getDef('text_categories_image_vignette'); ?></span>
                <span
                  class="col-md-1"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'images_categories.gif', $CLICSHOPPING_Categories->getDef('text_categories_image_visuel'), '40', '40'); ?></span>
                <span
                  class="col-md-7 main"><?php echo $CLICSHOPPING_Categories->getDef('text_categories_image_visuel'); ?></span>
              </div>
              <div class="col-md-12">
                <div class="adminformAide">
                  <div class="row">
                    <span
                      class="col-md-4 text-md-center float-md-left"><?php echo HTMLOverrideAdmin::fileFieldImageCkEditor('categories_image', null, '300', '300'); ?></span>
                    <span class="col-md-8 text-md-center float-md-right">
                        <div class="col-md-12">
                          <?php echo $CLICSHOPPING_ProductsAdmin->getInfoImage(null, $CLICSHOPPING_Categories->getDef('text_categories_image_vignette')); ?>
                        </div>
                        <div class="col-md-12 text-md-right">
                          <?php echo $CLICSHOPPING_Categories->getDef('text_categories_image_delete') . HTML::checkboxField('delete_image', 'yes', false); ?>
                        </div>
                      </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="separator"></div>
          <div class="alert alert-info" role="alert">
            <div><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/help.gif', $CLICSHOPPING_Categories->getDef('title_help_image')) . ' ' . $CLICSHOPPING_Categories->getDef('title_help_image') ?></div>
            <div class="separator"></div>
            <div><?php echo $CLICSHOPPING_Categories->getDef('help_image_categories'); ?></div>
          </div>
          <?php echo $CLICSHOPPING_Hooks->output('Categories', 'CategoriesContentTab4', null, 'display'); ?>
        </div>
        <?php
          //***********************************
          // extension
          //***********************************
          echo $CLICSHOPPING_Hooks->output('Categories', 'Page', null, 'display');
        ?>
      </div>
    </div>
  </div>
  </form>
</div>

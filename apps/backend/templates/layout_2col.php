<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>

    <div id="wrapper">

      <section id="header">
        <?php include_partial('global/menu') ?>
        <?php include_partial('global/mini-panel') ?>
      </section>

      <section id="sidebar">
        <?php if (!include_slot('sidebar')) : ?>
        sidebar
        <?php endif; ?>
      </section>
      <section id="content">
        <?php echo $sf_content ?>
      </section>
 
      <?php include_partial('global/footer') ?>
    </div>
    
  </body>
</html>

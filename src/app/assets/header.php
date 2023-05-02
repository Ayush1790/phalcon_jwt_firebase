<ul class=" list-inline list-unstyled text-center">
  <li class="list-inline-item col-2 p-2 btn btn-primary btn-outline-success bg-white ">
    <?php echo $this->tag->linkTo("product", "Add Products"); ?>
  </li>
  <li class="list-inline-item col-2 p-2 btn btn-primary btn-outline-success bg-white ">
    <?php echo $this->tag->linkTo("product/view", "View Products List"); ?>
  </li>
  <li class="list-inline-item col-2 p-2 btn btn-primary btn-outline-success bg-white ">
    <?php echo $this->tag->linkTo("order", "Add Order"); ?>
  </li>
  <li class="list-inline-item col-2 p-2 btn btn-primary btn-outline-success bg-white ">
    <?php echo $this->tag->linkTo("order/view", "View Order List"); ?>
  </li>
  <li class="list-inline-item col-2 p-2 btn btn-primary btn-outline-success bg-white ">
    <?php echo $this->tag->linkTo("setting", "Settings"); ?>
  </li>
</ul>

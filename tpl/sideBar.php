<aside>
  <div id="sidebar" class="nav-collapse bg-tea-secondary">
    <!-- sidebar menu start-->
    <ul class="sidebar-menu" id="nav-accordion">
      <p class="centered"><a href="profile.html"><img src="img/ui-sam.jpg" class="img-circle" width="80"></a></p>
      <h5 class="centered">Sam Soffes</h5>
      <li class="mt sub-menu">
        <a class="active" href="commodity.php">
          <i class="fa fa-dashboard"></i>
          <span>商品列表</span>
        </a>
      </li>
      <li class="sub-menu">
        <a href="./events.php">
          <i class="fa fa-desktop"></i>
          <span>活動列表</span>
        </a>
      </li>
      <li class="sub-menu">
        <a href="ad.php">
          <i class="fa fa-cogs"></i>
          <span>廣告資訊</span>
        </a>
      </li>
      <li class="sub-menu">
        <a href="./blog/login.php">
          <i class="fa fa-cogs"></i>
          <span>部落格</span>
        </a>
      </li>
      <li class="sub-menu">
        <a href="./login.php">
          <i class="fa fa-cogs"></i>
          <span>會員管理</span>
        </a>
      </li>
      <li class="sub-menu">
        <a href="./admin.php">
          <i class="fa fa-cogs"></i>
          <span>商家管理</span>
        </a>
      </li>

      
    </ul>
    <!-- sidebar menu end-->
  </div>
</aside>
<script>
  let href = location.href
  let arr = [...document.querySelectorAll('.sub-menu a')]
  arr.forEach(el => {
    if (el.href === href) {
      el.className = 'active';
    } else {
      el.className = '';
    }
  })
</script>

<template>
  <section class="page_404">
    <h1 class="text-center">404</h1>
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="four_zero_four_bg"></div>

          <div class="contant_box_404">
            <h3 class="h2">This page is not found</h3>

            <router-link to="/" class="link_404">Back To Home</router-link>
          </div>
        </div>
        <div class="col-sm-10 col-sm-offset-1 text-center"></div>
      </div>
    </div>
  </section>
</template>
  
<script>
export default {
  data() {
    return {
    }
  }
};
</script>
  
  <style scoped>
/* General Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html,
body {
  height: 100%;
  font-family: Arial, sans-serif;
}

.page_404 {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  background-color: #fff;
}

.four_zero_four_bg {
  background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
  height: 400px;
  width: 100%;
  object-fit: cover;
  background-position: center;
}

h1 {
  font-size: 80px;
  text-align: center;
  margin: 20px 0;
}

.four_zero_four_bg h3 {
  font-size: 80px;
}

.link_404 {
  color: #fff !important;
  padding: 10px 20px;
  background: var(--color);
  margin: 20px 0;
  text-decoration: none;
  display: inline-block;
  transition: 0.3s;
}

.link_404:hover {
  background: var(--bg-color);
}

.contant_box_404 {
  margin-top: -50px;
  text-align: center;
}

html,
body {
  height: 100%;
}
</style>
  
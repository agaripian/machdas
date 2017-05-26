<template>
  <div class="wrapper">
    <h1>{{ image.name }}</h1>
    <h2>{{ image.description }}</h2>
    <img :src="image.url"></img>
  </div>
</template>

<script>
export default {
  name: 'Beimage',
  props: ['image'],
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

  .wrapper {
    padding: 40px 0 40px;
  }

  h1, h2 {
    font-weight: normal;
  }

  h2 {
    font-size: 1.5rem;
  }

  img {
    padding: 10px 0 10px;
    width: 100%;
  }
</style>

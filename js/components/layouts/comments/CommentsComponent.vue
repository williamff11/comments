<template>
  <div>
    <br />
    <div class="card border col-sm-12 shadow">
      <div class="card-header linha">
        <h3 class="card-title col">Comentários</h3>
      </div>
      <div class="card-body">
        <createComment :folder_id="folder_id" @success="success" />

        <hr />

        <!-- <listagemComments :comments="comments" :loaded="loaded" :folder_id="folder_id" /> -->
        <listagemComments :comments="comments" :folder_id="folder_id" />
      </div>
    </div>
  </div>
</template>
<script>
import { mapState } from "vuex";

import CreateComment from "./partials/CreateComment";
import ListagemComments from "./ListagemComments";
export default {
  props: {
    folder_id: {
      required: true
    }
  },
  data() {
    return {
      loaded: false
    };
  },
  created() {
    this.loadComments();
  },
  computed: {
    ...mapState({
      comments: state => state.comments.items
    }),
    params() {
      return {
        folder_id: this.folder_id
      };
    }
  },
  methods: {
    loadComments() {
      return this.$store
        .dispatch("loadComments", { ...this.params })
        .finally(() => (this.loaded = true));
    },
    success() {
      this.loadComments();
    }
  },
  components: {
    CreateComment,
    ListagemComments
  }
};
</script>

<style scoped>
</style>
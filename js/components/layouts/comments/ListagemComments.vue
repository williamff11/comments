<template>
  <div>
    <div v-for="(comment, index) in commentsDad" :key="index">
      <div class="comment-dad">
        <div class="img-user col-md-2 text-center">
          <img
            v-if="comment.user.image"
            :src="[`/storage/${path}/${item.image}`]"
            :alt="comment.user.nomeCompleto"
            class="img-circle img-user-comment"
          />
          <img
            v-if="!comment.user.image"
            src="/storage/imagens/noAvatarUser.png"
            alt="Usuário sem Foto"
            class="img-circle img-user-comment"
          />
          <p>{{comment.user.nomeCompleto}}</p>
        </div>
        <div class="detalhe-comment col-md-10">
          <pre><p>{{comment.messageComment}}</p></pre>
        </div>
      </div>
      <div v-if="comment.comments_chield.length">
        <!-- <div v-for="(comment, index) in commentsDad" :key="index">
          <div class="comment-child">
            <div class="img-aluno col-md-2 text-center">
              <img
                src="/storage/imagens/noAvatarUser.png"
                alt="Usuário sem Foto"
                class="img-circle img-user-comment"
              />
              <img
                v-if="comment.comments_chield.user.image"
                :src="[`/storage/${path}/${item.image}`]"
                :alt="item.name"
                class="img-circle img-user-comment"
              />
              <img
                v-if="!comment.comments_chield.user.image"
                src="/storage/imagens/noAvatarUser.png"
                alt="Usuário sem Foto"
                class="img-circle img-user-comment"
              />
              <p>{{comment.comments_chield.user.nameCompleto}}</p>
            </div>
            <div class="detalhes-pergunta col-md-10">
              <pre><p>{{comment.comments_chield.messageComment}}</p></pre>
            </div>
          </div>
        </div>-->

        <h3>Tem Resposta</h3>
      </div>
      <div v-else>
        <h3>não tem resposta</h3>
      </div>
    </div>
    <!-- <div>
      <createComment :comment_id="comment.id" :folder_id="folder_id" @success="success" />
    </div>-->
  </div>
</template>

<script>
import { mapState } from "vuex";

import CreateComment from "./partials/CreateComment";
export default {
  props: {
    folder_id: {
      required: true
    },
    comments: {
      required: true
    }
  },
  methods: {
    loadComments() {
      return this.$store.dispatch("loadComments", { ...this.params });
    },
    success() {
      this.loadComments();
    }
  },
  computed: {
    params() {
      return {
        folder_id: this.folder_id
      };
    },
    // ...mapState({
    //   comments: state => state.comments.items
    // }),
    commentsDad: function() {
      return this.comments.filter(function(c) {
        return c.comment_id == null;
      });
    },
    commentsChild: function() {
      return this.commentsDad.filter(function(c) {
        return c.comment_id != null;
      });
    }
  },
  components: {
    CreateComment
  }
};
</script>

<style scoped>
.comment-dad {
  background: #ffc686;
  width: 100%;
  float: left;
  padding: 10px 0px;
  margin-bottom: 5px;
  border-radius: 4px;
  font-size: 18px;
  font-family: inherit;
}
.comment-child {
  background: #e1ffdd;
  float: right;
  width: 95%;
  margin-bottom: 5px;
  padding: 10px 0px;
  border-radius: 4px;
  font-size: 18px;
  font-family: inherit;
}
.img-user {
  font-weight: bold;
}
.img-user-comment {
  max-width: 100px;
}
.img-circle {
  border-radius: 50%;
}
/* .detalhe-comment {
} */
pre {
  background: transparent;
  border: none;
  max-width: 848px;
  white-space: pre-line;
}
</style>
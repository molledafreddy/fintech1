  <template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container" :class="modalSize">

          <div class="modal-header">
            <slot name="header">
              default header
            </slot>
          </div>

          <div class="modal-body">
            <slot name="body">
              default body
            </slot>
          </div>

          <div class="modal-footer">
            <slot name="footer">
                
                <button class="modal-default-button btn btn-success" @click="$emit('save')">
                  <i class="fa fa-hourglass-end" aria-hidden="true"></i>
                    Terminar
                </button>
                <button class="modal-default-button btn btn-warning" @click="$emit('close')">
                  <i class="fa fa-times-circle" aria-hidden="true"></i>
                    Atrás
                </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
<script>
    export default{
        props:['validForm', 'modalSize'],
        data(){
            return{
                //
            }
        }

    }
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.sm {
  width: 35%;
}

.md {
  width: 45%;
}

.lg {
  width: 75%;
}

.xl {
  width: 95%;
}

.modal-container {
  width: 45%;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
  margin-top: 0;
  color: #2579a9;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
  margin: 3px;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
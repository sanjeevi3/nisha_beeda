<script>
const openModal=(modal,backDrop)=>{
  document.getElementById(modal).className="my-modal modal-show "  
  document.getElementById(backDrop).className="back-drop"
}
const closeModal=(modal,backDrop)=>{
  document.getElementById(modal).className="my-modal modal-hide "  
  document.getElementById(backDrop).className="back-drop d-none "
}
</script>
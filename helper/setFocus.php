<script>
const setFocus=()=>{
for(let i=0;i<document.getElementsByClassName("form-control").length;i++){
    if(document.getElementsByClassName("form-control")[i].value==""){
        document.getElementsByClassName("form-control")[i].focus()
        break
    }
}
}
</script>

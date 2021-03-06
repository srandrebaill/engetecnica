<!-- Seleciona Obra a Gerenciar-->
<div id="selecionar_obra" class="row selecionar_obra">
    <small class="col-12">Gerenciar Obra</small>
    <form class="col-12 selecionar_obra_form" method="POST" action="/index/selecionar_obra" class="selecionar_obra_form">
        <select 
            class="select2 form-control" onchange="selecionar_obra.select2Obra()" 
            v-model="id_obra" id="selecionar_id_obra" id="id_obra_gerencia" name="id_obra_gerencia"
        >
            <option v-if="!user.id_obra_gerencia && user.id_obra" :value="user.id_obra">{{ formataDescricao(obras.find(ob => ob.id_obra == user.id_obra)) }}</option>
            <option v-if="!user.id_obra" :value="null">Selecione uma Obra</option>
            <option v-for="obra in filterObras" :value="obra.id_obra">{{ formataDescricao(obra) }}</option>
        </select>
        <input type="hidden" name="redirect_url" id="redirect_url" v-model="redirect_url">
        <button :disabled="!enableButton" type="submit" class="btn btn-primary">OK</button>
    </form>
</div>

<script>
    var user = JSON.parse('<?php echo json_encode($user); ?>') || null;
    var obras = JSON.parse(`<?php 
        if ($user->nivel == 1) {
            echo json_encode($obras);
        } else {
            echo json_encode([]);
        }
    ?>`);

    var selecionar_obra = new Vue({
        el: "#selecionar_obra",
        data(){
            return {
                user: user,
                id_obra: user.id_obra_gerencia ? parseInt(user.id_obra_gerencia) : user.id_obra,
                obras: obras,
                selection: null, 
                redirect_url: window.location.href,
            }
        },
        computed: {
            enableButton(){
                return this.id_obra && (this.user.id_obra_gerencia != this.id_obra)
            },
            filterObras(){
                return !this.user.id_obra_gerencia ? this.obras.filter(ob => ob.id_obra !== this.user.id_obra) : this.obras;
            }
        },
        methods: {
            formataDescricao(obra){
                return `${obra.codigo_obra} | ${obra.endereco}`
            },
            seleciona_obra(){
                window.$.ajax({
                    method: "POST",
                    url: `${base_url}index/selecionar_obra`,
                    data: {
                        id_obra_gerencia: this.id_obra,
                        redirect_url: window.location.href
                    }
                })
                .done((response) => {
                    if(!response.success) {
                        Swal.fire({
                            title: 'Ocorreu um erro',
                            text: "Ocorreu um erro ao selecionar a obra!",
                            icon: 'error',
                            showCancelButton: false
                        })
                    }
                    this.id_obra = parseInt(response.id_obra_gerencia)
                    //window.location.href = window.location.href
                })
            },
            select2Obra(){
                let element = document.getElementById("select2-selecionar_id_obra-container")
                if (element) {this.selection = element.title}
            }
        },
        watch: {
            selection(){
                let obra = this.obras.find(ob => this.formataDescricao(ob) == this.selection)
                if (obra) {this.id_obra = obra.id_obra}
            }
        },
        created(){
            setTimeout(() => {this.select2Obra()}, 500)
        }
    })
</script>
function abrirModalNovo() {
    document.getElementById('formExercicio').reset();
    document.getElementById('acao_form').value = 'inserir'; 
    document.getElementById('id_exercicio').value = '';
    document.getElementById('tituloModal').innerText = "Adicionar Exercício";
    var checkboxes = document.getElementsByName('grupo_secundario[]');
    for(var i=0; i<checkboxes.length; i++) checkboxes[i].checked = false;
    document.getElementById('modalCriar').style.display = 'flex';
}
function editarExercicio(dados) {
    document.getElementById('acao_form').value = 'editar';
    document.getElementById('id_exercicio').value = dados.id;
    document.getElementById('tituloModal').innerText = "Editar Exercício: " + dados.nome;
    document.getElementById('input_nome').value = dados.nome;
    document.getElementById('input_equipamento').value = dados.equipamento;
    document.getElementById('input_primario').value = dados.grupo_muscular;
    var checkboxes = document.getElementsByName('grupo_secundario[]');
    for(var i=0; i<checkboxes.length; i++) checkboxes[i].checked = false;
    if (dados.grupo_secundario) {
        var secundarios = dados.grupo_secundario.split(',').map(s => s.trim()); 
        for (var i = 0; i < checkboxes.length; i++) {
            if (secundarios.includes(checkboxes[i].value)) {
                checkboxes[i].checked = true;
            }
        }
    }
    document.getElementById('modalCriar').style.display = 'flex';
}
function fecharModal() {
    document.getElementById('modalCriar').style.display = 'none';
}
window.onclick = function(event) {
    var modal = document.getElementById('modalCriar');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
function filtrarBiblioteca() {
    var texto = document.getElementById('buscaExercicio').value.toLowerCase();
    var musculo = document.getElementById('filtroMusculo').value;
    var equip = document.getElementById('filtroEquip').value;
    var itens = document.getElementsByClassName('lib-item');
    for (var i = 0; i < itens.length; i++) {
        var item = itens[i];
        var nomeItem = item.getAttribute('data-nome');
        var musculoItem = item.getAttribute('data-musculo');
        var equipItem = item.getAttribute('data-equip');
        var mostrar = true;
        if (texto !== "" && !nomeItem.includes(texto)) {
            mostrar = false;
        }
        if (musculo !== "" && musculoItem !== musculo) {
            mostrar = false;
        }
        if (equip !== "" && equipItem !== equip) {
            mostrar = false;
        }
        if (mostrar) {
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    }
}
function adicionarExercicio(id) {
    document.getElementById('id_exercicio_add').value = id;
    document.getElementById('formTreino').submit();
}
function mascaraTempo(input) {
    var valor = input.value.replace(/\D/g, "");
    if (valor.length > 4) valor = valor.slice(0, 4);
    if (valor.length > 2) {
        valor = valor.slice(0, 2) + ":" + valor.slice(2);
    }
    input.value = valor;
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
        if (texto !== "" && !nomeItem.includes(texto)) mostrar = false;
        if (musculo !== "" && musculoItem !== musculo) mostrar = false;
        if (equip !== "" && equipItem !== equip) mostrar = false;
        item.style.display = mostrar ? "flex" : "none";
    }
}
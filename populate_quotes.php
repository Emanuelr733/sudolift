<?php
require_once 'controller/clsConexao.php';
$conexao = new clsConexao();

$citacoes = [
    // Arnold Schwarzenegger
    ["A dor que você sente hoje será a força que você sentirá amanhã.", "Arnold Schwarzenegger"],
    ["O último 1% é o que conta.", "Arnold Schwarzenegger"],
    ["Se você não vê o resultado, continue. O progresso é invisível no começo.", "Arnold Schwarzenegger"],
    ["A mente é o limite. Enquanto a mente consegue imaginar o fato de que você pode fazer algo, você consegue fazer.", "Arnold Schwarzenegger"],

    // Ronnie Coleman
    ["Todo mundo quer ser fisiculturista, mas ninguém quer levantar pesos pesados.", "Ronnie Coleman"],
    ["Leve e fácil, leve e fácil!", "Ronnie Coleman"],

    // Filósofos / Históricos
    ["A coragem não é a ausência do medo, mas o triunfo sobre ele.", "Nelson Mandela"],
    ["Nós somos o que repetidamente fazemos. A excelência, portanto, não é um feito, mas um hábito.", "Aristóteles"],
    ["Se você quer algo que nunca teve, precisa fazer algo que nunca fez.", "Thomas Jefferson"],
    ["O segredo de progredir é começar.", "Mark Twain"],

    // Fitness & Disciplina
    ["A motivação é passageira. A disciplina é eterna.", "Desconhecido"],
    ["Não diminua a meta. Aumente o esforço.", "Desconhecido"],
    ["Seu único limite é você.", "Desconhecido"],
    ["Transforme 'eu queria' em 'eu vou'.", "Desconhecido"],
    ["O corpo que você quer esperar do outro lado do esforço que você não quer fazer.", "Desconhecido"],
    ["Disciplina é escolher o que você quer mais em vez do que você quer agora.", "Abraham Lincoln"],
    ["O suor é a gordura chorando.", "Desconhecido"],
    ["Treine enquanto eles dormem, estude enquanto eles se divertem, persista enquanto eles descansam, e então, viva o que eles sonham.", "Desconhecido"],
    ["Não pare quando estiver cansado. Pare quando tiver terminado.", "David Goggins"],
    ["Você não conhece seus limites até estar disposto a ultrapassá-los.", "Desconhecido"],

    // Esportistas
    ["Eu odiava cada minuto dos treinos, mas dizia para mim mesmo: Não pare. Sofra agora e viva o resto de sua vida como um campeão.", "Muhammad Ali"],
    ["O impossível é apenas uma grande palavra jogada ao vento.", "Muhammad Ali"],
    ["Você perde 100% dos chutes que não dá.", "Wayne Gretzky"],
    ["O sucesso não é acidental. É trabalho duro, perseverança, aprendizado, estudo, sacrifício e, acima de tudo, amor pelo que você está fazendo.", "Pelé"],

    // Geral / Motivacional
    ["Acredite que você pode, assim você já está no meio do caminho.", "Theodore Roosevelt"],
    ["O futuro pertence àqueles que acreditam na beleza de seus sonhos.", "Eleanor Roosevelt"],
    ["Não importa o quão devagar você vá, desde que você não pare.", "Confúcio"],
    ["Sucesso é a soma de pequenos esforços repetidos dia após dia.", "Robert Collier"],
    ["A melhor maneira de prever o futuro é criá-lo.", "Peter Drucker"],
    ["Não espere. O tempo nunca será o 'certo'.", "Napoleon Hill"],

    // Crossfit / Funcional
    ["Se fosse fácil, todo mundo faria.", "Desconhecido"],
    ["Sua zona de conforto vai te matar.", "Desconhecido"],
    ["Mais forte do que ontem.", "Desconhecido"],
    ["Não conte os dias, faça os dias contarem.", "Muhammad Ali"],

    // Add more...
    ["Faça hoje o que seu eu futuro vai agradecer.", "Desconhecido"],
    ["Foco, força e fé.", "Desconhecido"],
    ["A persistência é o caminho do êxito.", "Charles Chaplin"],
    ["Quem não luta pelo futuro que quer, deve aceitar o futuro que vier.", "Desconhecido"],
    ["Vencedores não são pessoas que nunca falham, são pessoas que nunca desistem.", "Desconhecido"],
    ["A vida começa no final da sua zona de conforto.", "Neale Donald Walsch"],
    ["Não se sabote, se supere.", "Desconhecido"],
    ["Acorde com determinação, vá dormir com satisfação.", "Desconhecido"],
    ["Sem luta, não há vitória.", "Desconhecido"],
    ["O único lugar onde o sucesso vem antes do trabalho é no dicionário.", "Vidal Sassoon"],
    ["Seja mais forte que sua melhor desculpa.", "Desconhecido"]
];

$count = 0;
foreach ($citacoes as $c) {
    $desc = mysqli_real_escape_string($conexao->getConexao(), $c[0]);
    $autor = mysqli_real_escape_string($conexao->getConexao(), $c[1]);

    // Evita duplicatas exatas
    $res = $conexao->executaSQL("SELECT id FROM citacoes WHERE descricao = '$desc'");
    if (mysqli_num_rows($res) == 0) {
        $sqlInsert = "INSERT INTO citacoes (descricao, autor) VALUES ('$desc', '$autor')";
        $conexao->executaSQL($sqlInsert);
        $count++;
    }
}

echo "Inseridas $count novas citações no banco de dados.\n";

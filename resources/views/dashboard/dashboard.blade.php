@extends('adminlte::page')


@section('title', 'Dashboard')



@section('content_header')
<h1>Dashboard</h1>
@endsection

@section('content')
    @include('dashboard.partials.shortcuts')
    @include('dashboard.partials.charts')
    
    {{-- Verificação do token --}}
    @include('layouts.token_check')

    <script>
        Parse.initialize("LQRzTKbNgKj7YGJSYcIZkGSFC1S8hVYOpGBBOYAd", "OEHlHd5xDbfSXakcrbbKxztI5IQm8f6AxwYB6OhI");
Parse.serverURL = "https://parseapi.back4app.com";
const inputNome = document.getElementById("nome");
const inputIdade = document.getElementById("idade");
const btnSalvar = document.getElementById("btnSalvar");
const btnListar = document.getElementById("btnListar");
const ulListaPessoas = document.getElementById("listaPessoas");

async function salvarPessoa() {
const nome = inputNome.value;
const idade = parseInt(inputIdade.value);
if (!nome || !idade) {
alert("Por favor, preencha nome e idade corretamente.");
return;
}

const Pessoa = Parse.Object.extend("Pessoa");
const pessoa = new Pessoa();
pessoa.set("nome", nome);
pessoa.set("idade", idade);
try {
await pessoa.save();
alert("Pessoa cadastrada com sucesso!");
inputNome.value = "";
inputIdade.value = "";
} catch (erro) {
console.error("Erro ao salvar pessoa:", erro);
alert("Ocorreu um erro ao salvar a pessoa.");
}
}

async function listarPessoas() {
const Pessoa = Parse.Object.extend("Pessoa");
const query = new Parse.Query(Pessoa);
try {
const resultados = await query.find();
ulListaPessoas.innerHTML = "";
resultados.forEach((pessoa) => {
const li = document.createElement("li");
li.textContent = Nome: ${pessoa.get("nome")} | Idade: ${pessoa.get("idade")};
ulListaPessoas.appendChild(li);
});
if (resultados.length === 0) {
ulListaPessoas.innerHTML = "<li>Nenhuma pessoa encontrada.</li>";
}
} catch (erro) {
console.error("Erro ao listar pessoas:", erro);
alert("Ocorreu um erro ao listar as pessoas.");
}
}

btnSalvar.addEventListener("click", salvarPessoa);
btnListar.addEventListener("click", listarPessoas);
    </script>
@endsection

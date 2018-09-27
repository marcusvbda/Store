<?php
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();    
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');


        // $this->call(tenantsSeed::class);  
        // $this->call(gruposAcessoSeed::class);
        $this->call(grupoPermissaoSeed::class);
        $this->call(permissoesSeed::class);
        $this->call(gruposAcessoPermissoes::class);
        // $this->call(parametrosSeed::class);
        // $this->call(TenantParametrosSeed::class);
        // $this->call(usuariosSeed::class);
        // $this->call(TenantUsuariosSeed::class);
        // $this->call(CategoriaRelatorioSeed::class);
        // $this->call(RelatorioSeed::class);
        // $this->call(ModeloEmailSeed::class);
        // $this->call(marcasSeed::class);
        // $this->call(produtoCategoriaSeed::class);
        // $this->call(especificacoesProdutoSeed::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}

class especificacoesProdutoSeed extends Seeder 
{
    public function run()
    {
        DB::table('skuEspecificacao')->truncate();
        DB::table('skuEspecificacao')->insert(
        [
            "id"          => uniqid(),
            "nome"        => "Voltagem",
            "tipoCampo"   => "SELECT",
            "opcoes"      => "110,220,Bivolt",
        ]);
    }
}


class produtoCategoriaSeed extends Seeder 
{
    public function run()
    {
        DB::table('produtoCategoria')->truncate();
        $id =  uniqid();
        DB::table('produtoCategoria')->insert(
        [
            "id"          => $id,
            "nome"        => "IMPRESSORA"
        ]);
        DB::table('produtoSubCategoria')->insert(
        [
            "id"          => uniqid(),
            "categoriaid" => $id,
            "nome"        => "JATO DE TINTA"
        ]);
        DB::table('produtoSubCategoria')->insert(
        [
            "id"          => uniqid(),
            "categoriaid" => $id,
            "nome"        => "LASER"
        ]);
    }
}

class marcasSeed extends Seeder 
{
    public function run()
    {
        DB::table('produtoMarca')->truncate();
        DB::table('produtoMarca')->insert(
        [
            "id"          => uniqid(),
            "nome"        => "Xerox"
        ]);
        DB::table('produtoMarca')->insert(
        [
            "id"          => uniqid(),
            "nome"        => "Brother"
        ]);
    }
}

class tenantsSeed extends Seeder 
{
    public function run()
    {
        DB::table('tenants')->truncate();
        DB::table('tenants')->insert(
        [
            "id"          => uniqid(),
            "cnpj"        => "11.111.11/1111-11",
            "nome"        => "Nome da empresa teste",
            "razao"       => "Razao da empresa teste",
            "cidade"      => "Marilia",
        ]);
    }
}

class gruposAcessoSeed extends Seeder 
{
    public function run()
    {
        DB::table('gruposAcesso')->truncate();        
        DB::table('gruposAcesso')->insert(
        [ 
            "id"         => uniqid(),
            "nome"       => "administrador"
        ]);
    }
}


class grupoPermissaoSeed extends Seeder 
{
    public function run()
    { 
        DB::table('grupoPermissao')->truncate();        
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Parametros" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Grupos de acesso" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Usuários" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Empresas" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Modelos de email" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Relatórios" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "Produtos" ]); 
        DB::table('grupoPermissao')->insert(["id" => uniqid(), "descricao" => "CMS" ]); 
    }
}


class permissoesSeed extends Seeder 
{
    public function run()
    {
        $grupos = DB::table("grupoPermissao")->get();
        $cont = 0;
        DB::table('permissoes')->truncate();        
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[0]->id, "nome" => "get_parametros",  "descricao" => "Acessar parametros" ]);  
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[0]->id, "nome" => "put_parametros" , "descricao" => "Alterar parametros" ]);    
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[1]->id, "nome" => "get_gruposAcesso", "descricao" => "Acessar grupos de acesso"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[1]->id, "nome" => "get_config_email", "descricao" => "Acessar e alterar configurações de email"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[1]->id, "nome" => "post_gruposAcesso", "descricao" => "Cadastrar grupos de acesso"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[1]->id, "nome" => "put_gruposAcesso", "descricao" => "Alterar grupos de acesso"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[1]->id, "nome" => "delete_gruposAcesso", "descricao" => "Delete grupos de acesso"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[2]->id, "nome" => "get_usuarios", "descricao" => "Acessar usuários"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[2]->id, "nome" => "post_usuarios", "descricao" => "Cadastrar usuários"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[2]->id, "nome" => "put_usuarios", "descricao" => "Alterar usuários"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[2]->id, "nome" => "delete_usuarios", "descricao" => "Delete usuários"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[2]->id, "nome" => "put_emailSenha", "descricao" => "Alterar email e senha de usuários"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[3]->id, "nome" => "get_tenants", "descricao" => "Visualizar empresa"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[3]->id, "nome" => "post_tenants", "descricao" => "Cadastrar empresa"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[3]->id, "nome" => "put_tenants", "descricao" => "Alterar polos"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[3]->id, "nome" => "delete_tenants", "descricao" => "Excluir empresa"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[4]->id, "nome" => "post_modelos_email", "descricao" => "Cadastrar modelos de email"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[4]->id, "nome" => "get_modelos_email", "descricao" => "Ver modelos de email"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[4]->id, "nome" => "put_modelos_email", "descricao" => "Editar modelos de email"]); 
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[4]->id, "nome" => "delete_modelos_email", "descricao" => "Excluir modelos de email"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[5]->id,"nome" => "get_relatorios", "descricao" => "Acesso aos relatórios"]);

        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "get_produtos", "descricao" => "Acesso aos produtos"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "post_produtos", "descricao" => "Cadastrar os produtos"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "put_produtos", "descricao" => "Alterar produtos"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "delete_produtos", "descricao" => "Excluir produtos"]);

        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "get_marcas", "descricao" => "Acesso as marcas"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "post_marcas", "descricao" => "Cadastrar marcas"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "put_marcas", "descricao" => "Alterar marcas"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "delete_marcas", "descricao" => "Acesso as marcas"]);

        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "get_categoriaProduto", "descricao" => "Acesso as categorias de produto"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "post_categoriaProduto", "descricao" => "Cadastrar categorias de produto"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "put_categoriaProduto", "descricao" => "Alterar categorias de produto"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "delete_categoriaProduto", "descricao" => "Acesso as categorias de produto"]);

        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "get_especificacaoProduto", "descricao" => "Acesso as especificações de produto"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "post_especificacaoProduto", "descricao" => "Cadastrar especificações de produto"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "put_especificacaoProduto", "descricao" => "Alterar especificações de produto"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[6]->id,"nome" => "delete_especificacaoProduto", "descricao" => "Acesso as especificações de produto"]);

        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[7]->id,"nome" => "get_campoPersonalizado", "descricao" => "Acesso aos campos personalizados"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[7]->id,"nome" => "post_campoPersonalizado", "descricao" => "Cadastrar campos personalizados"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[7]->id,"nome" => "put_campoPersonalizado", "descricao" => "Alterar campos personalizados"]);
        DB::table('permissoes')->insert(["id"=>uniqid(), "grupoPermissaoId"=> $grupos[7]->id,"nome" => "delete_campoPersonalizado", "descricao" => "Excluir campos personalizados"]);

    }
}


class gruposAcessoPermissoes extends Seeder 
{
    public function run()
    {
        DB::table('grupoAcessoPermissoes')->truncate();                
        foreach(DB::table('gruposAcesso')->get() as $grupo)
        {        
            foreach(DB::table("permissoes")->get() as $permissao)
            {
                DB::table('grupoAcessoPermissoes')->insert(["grupoAcessoId"  => $grupo->id,"permissaoId" => $permissao->id]);
            }
        }
    }
}






class parametrosSeed extends Seeder 
{
    public function run()
    {
        DB::table('parametros')->truncate();
        DB::table('parametros')->insert(
        [
            "id"           =>  "casasDecimais",
            "label"        =>  "Casas decimais",
            "descricao"    =>  "Qtde de casas decimais após a virgula",
            "type"         =>  "number",
            "bootstrapCol" =>  2,
            "max"          =>  12,
            "min"          =>  1,
            "required"     =>  1,
            "step"         =>  1
        ]);
    }
}

class TenantParametrosSeed extends Seeder
{
    public function run()
    {
        DB::table('TenantParametros')->truncate();
        foreach(DB::table('tenants')->get() as $tenant)
        {
            DB::table('TenantParametros')->insert(
            [
                "parametroId"  =>  "casasDecimais",
                "valor"        =>  "2",
                "tenantId"     =>  $tenant->id
            ]);
        }
    }
}

class usuariosSeed extends Seeder 
{
    public function run()
    {
        DB::table('usuarios')->truncate();                        
        DB::table('usuarios')->insert(
        [
            "id"         =>  uniqid(),
            "tenantId"   =>   null,
            "grupoAcessoId"   =>  DB::table("gruposAcesso")->first()->id,
            "nome"       =>  "Administrador",
            "email"      =>  "root@root.com",
            "dtNascimento"    =>  "1992-01-01",
            "senha"      =>  md5("roottoor")
        ]);
    }
}

class TenantUsuariosSeed extends Seeder 
{
    public function run()
    {
        $tenantId = DB::table('tenants')->first()->id;
        DB::table('tenantsUsuarios')->truncate();                        
        foreach(DB::table('usuarios')->get() as $usuario)
        {
            DB::table('tenantsUsuarios')->insert(
            [
                "usuarioId"  =>  $usuario->id,
                "tenantId"   =>  $tenantId
            ]);
        }
    }
}

class CategoriaRelatorioSeed extends Seeder 
{
    public function run()
    {
        DB::table('categoriasRelatorio')->truncate();                        
        DB::table('categoriasRelatorio')->insert(
        [
            "id"     => uniqid(),
            "nome"   =>  "Usuários"
        ]);
    }
}

class ModeloEmailSeed extends Seeder
{
   public function run()
    {
        DB::table('modeloEmail')->truncate();                        
        DB::table('modeloEmail')->insert(
        [
            "id"     => uniqid(),
            "nome"   =>  "Usuários",
            "tenantId"  => DB::table('tenants')->first()->id,
            "assunto"   => "Assunto do modelo de email teste",
            "nome"  => "Modelo teste",
            "modelo"  => "<h1>Modelo</h1>"
        ]);
    } 
}

class RelatorioSeed extends Seeder 
{
    public function run()
    {
        DB::table('relatorios')->truncate();      
        $tableNameUsuarios = 'usuarios';
        $tableNameGrupos = 'gruposAcesso';
        DB::table('relatorios')->insert(
        [
            "id"   => uniqid(),
            "nome"   =>  "Usuários Cadastrados",
            "categoriaId" => DB::table("categoriasRelatorio")->get()[0]->id,
            "permissao" => "get_relatorios",
            "campos" => "Id|Nome|Email|Grupo de Acesso",
            "modoPDF" => "portrait",
            "query" => "select ".
                    " {$tableNameUsuarios}.id as Id, {$tableNameUsuarios}.nome as Nome, {$tableNameUsuarios}.email as Email, ".
                    " {$tableNameGrupos}.nome as Grupo_de_Acesso from {$tableNameUsuarios} ".
                    " join {$tableNameGrupos} on {$tableNameGrupos}.id = {$tableNameUsuarios}.grupoAcessoId ".
                    " where 1=1 ".
                    " #and {$tableNameUsuarios}.id like'%{Id}%'#".
                    " #and {$tableNameUsuarios}.nome like'%{Nome}%'#".
                    " #and {$tableNameUsuarios}.grupoAcessoId='{Grupo_de_acesso,select * from {$tableNameGrupos}}'# "
        ]);

    }
}


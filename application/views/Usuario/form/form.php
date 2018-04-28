
<div class="form">
      
      <ul class="tab-group">

          <li class="tab <?php if($signup) echo 'active' ?>"><a href="#signup">Cadastra-se</a></li>
          <li class="tab <?php if($login) echo 'active' ?>"><a href="#login">Entrar</a></li>

      </ul>
      
      <div class="tab-content">
        <div id="signup" style="<?php if($signup) echo 'display: block;'; else echo 'display: none;';  ?>">   
          <h1>Cadastre-se gratuitamente</h1>
          
          <?php if( isset($errorSignup) ): ?>
            <?php foreach ($errorSignup as $value): ?>
              <div class="alert" role="alert">
                <?php echo $value ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>


          <form action="<?php echo base_url('usuario/create') ?>" method="post">
          
          <div class="top-row">
            <div class="field-wrap">
              <label class="<?php echo isset($input['desnome']) ? "active" : "" ?>" >
                Primeiro Nome<span class="req">*</span>
              </label>
              <input type="text" name="desnome" required autocomplete="off" value="<?php echo isset($input['desnome']) ? $input['desnome'] : "" ?>" >
            </div>
        
            <div class="field-wrap">
              <label class="<?php echo isset($input['dessobrenome']) ? "active" : "" ?>" >
                Sobrenome<span class="req">*</span>
              </label>
              <input type="text" name="dessobrenome" required autocomplete="off" value="<?php echo isset($input['dessobrenome']) ? $input['dessobrenome'] : "" ?>" >
            </div>
          </div>

          <div class="field-wrap">
            <label class="<?php echo isset($input['desemail']) ? "active" : "" ?>" >
              Email<span class="req">*</span>
            </label>
            <input type="email"required name="desemail" autocomplete="off" value="<?php echo isset($input['desemail']) ? $input['desemail'] : "" ?>" >
          </div>

          <div class="field-wrap">
            <label class="<?php echo isset($input['deslogin']) ? "active" : "" ?>" >
              Login<span class="req">*</span>
            </label>
            <input type="text" required name="deslogin" autocomplete="off" value="<?php echo isset($input['deslogin']) ? $input['deslogin'] : "" ?>" >
          </div>
          
          <div class="field-wrap">
            <label>
              Senha<span class="req">*</span>
            </label>
            <input type="password" name="dessenha" required autocomplete="off"/>
          </div>
          
          <button type="submit" class="button button-block"/>Cadastrar</button>

          </form>

        </div>
        
        <div id="login" style="<?php if($login) echo 'display: block;'; else echo 'display: none;';  ?>">   
          <h1>Bem vindo(a)!</h1>

          <?php if( isset($errorLogin) ): ?>
              <div class="alert" role="alert">
                <?php echo $errorLogin ?>
              </div>
          <?php endif; ?>
          
          <form action="<?php echo base_url('usuario/logon') ?>" method="post">
          
            <div class="field-wrap">
            <label class="<?php echo isset($input['deslogin']) ? "active" : "" ?>">
              Login<span class="req">*</span>
            </label>
            <input type="text"  name = "deslogin" required autocomplete="off" value="<?php echo isset($input['deslogin']) ? $input['deslogin'] : "" ?>" />
          </div>
          
          <div class="field-wrap">
            <label>
              Senha<span class="req">*</span>
            </label>
            <input type="password" name="dessenha" required autocomplete="off"/>
          </div>
          
          <p class="forgot"><a href="#">Esqueçeu a senha?</a></p>

          
          <button class="button button-block"/>Entrar</button>
          
          </form>

        </div>
        
      </div><!-- tab-content -->
      
</div> <!-- /form -->

<div class="row justify-content-md-center p-2">
    <form method="POST" class="col-12 col-md-6 align-self-center jumbotron p-4">
    <h1 class="text-center">Форма</h1>
    <div class="form-group">
      <input type="name" placeholder="Имя" name="name" class="form-control <?php if($errors['name']){echo 'border border-danger';} ?>" value="<?php echo $values['name']; ?>"><br>
      <input type="email" placeholder="Email" name="email" class="form-control <?php if($errors['email']){echo 'border border-danger';} ?>" value="<?php echo $values['email']; ?>"><br>
      <input type="number" placeholder="Год рождения" name="year" class="form-control <?php if($errors['year']){echo 'border border-danger';} ?>" value="<?php echo $values['year']; ?>"><br>
      </div>
      <h6>Пол</h6>
       <div class="form-group row col-6 justify-content-between">
      <div class="form-check">
      <label class="form-check-lable" for="gender">
        <input type="radio" value=0 name="gender" id="gender" class="form-check-input" <?php if(!$values['gender']){echo 'checked';}?>>
        М.
      </label>
      </div>
      <div class="form-check">
      <label class="form-check-lable" for="gender">
        <input type="radio" value=1 name="gender" id="gender" class="form-check-input" <?php if($values['gender']){echo 'checked';}?>>
        Ж.
      </label><br>
      </div>
      </div>
      <h6>Количество конечностей</h6>
      <div class="form-group row col-12 justify-content-between">
      	<div class="form-check">
      <input type="radio" value=1 name="bodyparts" class="form-check-input" <?php if($values['bodyparts']==1){echo 'checked';}?>>
        <label for="one" class="form-check-label">1</label>
        </div>
        <div class="form-check">
      <input type="radio" value=2 name="bodyparts" class="form-check-input" <?php if($values['bodyparts']==2){echo 'checked';}?>>
        <label for="two" class="form-check-label">2</label>
        </div>
        <div class="form-check">
      <input type="radio" value=3 name="bodyparts" class="form-check-input" <?php if($values['bodyparts']==3){echo 'checked';}?>>
        <label for="three" class="form-check-label">3</label>
        </div>
        <div class="form-check">
      <input type="radio" value=4 name="bodyparts" class="form-check-input" <?php if($values['bodyparts']==4){echo 'checked';}?>>
        <label for="four" class="form-check-label">4</label>
        </div>
        </div>
        <h6>Сверхспособности</h6>
        <div class="form-group">
      <select multiple name="powers[]" class="form-control">
       <?php 
            $powers = unserialize($values['powers']);
            if(!$powers) $powers = array();
       ?>
        <option value=1 <?php if(in_array(1, $powers)) echo 'selected'; ?>>Бессмертие</option>
        <option value=2 <?php if(in_array(2, $powers)) echo 'selected'; ?>>Прохождение сквозь стены</option>
        <option value=3 <?php if(in_array(3, $powers)) echo 'selected'; ?>>Левитация</option>
      </select>
      <br>
      </div>
      <h6>Биография</h6>
      <div class="form-group">
      <textarea name="bio" class="form-control <?php if($errors['bio']){echo 'border border-danger';} ?>"><?php echo $values['bio']; ?></textarea>
      <br>
      <div class="form-check"
      <label for="agreed" class="form-check-label">
        <input type="checkbox" name="agreed" id="agreed" class="form-check-input" required>
        с контрактом ознакомлен
      </label>
      </div>
      <br>
      <input type="submit" value="Отправить" class="form-control">
      </div>
    </form>
</div>
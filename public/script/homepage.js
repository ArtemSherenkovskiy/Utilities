$(document)
  .ready(function() {

    var
      changeSides = function() {
        $('.ui.shape')
          .eq(0)
            .shape('flip over')
            .end()
          .eq(1)
            .shape('flip over')
            .end()
          .eq(2)
            .shape('flip back')
            .end()
          .eq(3)
            .shape('flip back')
            .end()
        ;
      },
        validationRules = {
            email: {
                identifier  : 'email',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter an e-mail'
                    },
                    {
                        type   : 'email',
                        prompt : 'Please enter a valid e-mail'
                    }
                ]
            },
            second_name: {
                identifier  : 'second_name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter your second name'
                    },
                    {
                        type   : 'length[1]',
                        prompt : 'Please enter a valid second name'
                    },
                    {
                        type   :'regExp[/^[A-zа-яА-Я\`ґєҐЄ´ІіЇї\s]+$/]',
                        prompt : 'Please enter a valid second name'
                    }
                ]
            },
            first_name: {
                identifier  : 'first_name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter your name'
                    },
                    {
                        type   : 'length[1]',
                        prompt : 'Please enter a valid name'
                    },
                    {
                        type   : 'regExp[/^[A-zа-яА-Я\`ґєҐЄ´ІіЇї\s]+$/]',
                        prompt : 'Please enter a valid name'
                    }
                ]
            },
            password: {
                identifier  : 'password',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter a valid password'
                    },
                    {
                        type   : 'length[6]',
                        prompt : 'Please enter min 6 length password'
                    }
                ]
            },
            password_confirmation: {
                identifier  : 'password_confirmation',
                rules: [

                    {
                        type   : 'match[password]',
                        prompt : 'Пароли не равны'
                    }
                ]
            }
        }
        ;

    $('.ui.dropdown')
      .dropdown({
        on: 'hover'
      })
    ;

      $('#register.ui.form')
          .form(validationRules, {
              inline : true,
              on     : 'blur'
          })
      ;

    $('.masthead .information')
      .transition('scale in', 1000)
    ;

    setInterval(changeSides, 3000);

  })
;
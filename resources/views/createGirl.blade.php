@extends('layouts.blog3', ['title' => 'Создание анкеты'])

@section('content')

    <!-- Main jumbotron for a primary marketing message or call to action -->

    <form action="{{route('girlsCreate')}}" method="post" enctype="multipart/form-data" novalidate>
        {{ csrf_field() }}

        <div class="form-group">
            <label for="title">Введите имя, которое будет отображаться:</label>
            <input type="text" class="form-control" id="name" name="name" required placeholder="Ведите имя"  value={{ old('name') }} >
        </div>
        @if($errors->has('name'))
            <font color="red">  {{$errors->first('name')}}</font>
        @endif

        <div class="control-group2" ng-class="{true: 'error'}[submitted && form.pas.$invalid]">
            <div class="form-group">
                <label for="phone">Ваш телефон:</label>
                <!--   <input type="tel" class="form-control" id="phone" name="phone" pattern="^\(\d{3}\)\d{3}-\d{2}-\d{2}$" required></input>-->
                <input type="tel" class="form-control" id="phone" name="phone" value="{{$phone}}"  readonly required></input>
            </div>
        </div>
        <b>  Пол:</b> <br>
        <input type="radio" id="contactChoice1"
               name="sex" value="famele" checked>
        <label for="contactChoice1">Женский</label>

        <input type="radio" id="contactChoice2"
               name="sex" value="male">
        <label for="contactChoice2">Мужской</label>

        <br>
        <label for="age">Возраст:
            <input type="number" name="age" min="18" value=18 onkeypress="return isNumber(event)" checked>
            @if($errors->has('age'))
                <font color="red"> {{$errors->first('age')}}</font>
            @endif
        </label><br>

        <label for="age">Рост:
            <input type="number" name="height" min="100" value="160" onkeypress="return isNumber(event)" >
        </label><br>
        <label for="age">Вес:
            <input type="number" name="weight" min="45" step="1" value="45" onkeypress="return isNumber(event)" pattern="[^@]+@[^@]+\.[0-9]{2,3}" >
        </label><br>


        <b>  С кем хотите познакомиться:</b> <br>
        <input type="radio" id="contactmet"
               name="met" value="famele">
        <label for="contactChoice1">c женщиной</label>
        <br>
        <input type="radio" id="contactmet2"
               name="met" value="male" checked>
        <label for="contactChoice2">с мужчиной</label>
        <br>
        <label>Страна:
            <select style="width: 200px" class="country" class="form-control input-sm" name="country" id="country">
                <option value="-">-</option>
               @foreach($countries as $contry)
                    <option value="{{$contry->id_country}}">{{$contry->name}}</option>
                @endforeach
            </select>
        </label>
        <label>Регион:
            <select style="width: 200px" class="region" name="region" class="form-control input-sm" id="region">

                <option value=""></option>

            </select>
        </label>

        <label>Город:
            <select id="city" class="city" style="width: 200px" name="city" >
                <option value=""></option>
            </select>
        </label>
        <script >

            $('#country').on('change',function (e) {

                var country_id=e.target.value;
                console.log(country_id);
                //ajax
                //   $('#city').empty();
                $.get('/findRegions?country_id='+country_id,function (data) {
                    $('#region').empty();
                    $('#city').empty();
                    $.each(data,function (index,subcatObj){
                        $('#region').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                    })
                })
                var region_id=e.target.value;
                console.log(region_id);
                //ajax
                $.get('/findCitys?region_id='+region_id,function (data) {
                    $('#city').empty();
                    $.each(data,function (index,subcatObj){
                        $('#city').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                    })
                })

            })

            $('#region').on('change',function (e) {
                var region_id=e.target.value;
                console.log(region_id);
                //ajax
                $.get('/findCitys?region_id='+region_id,function (data) {
                    $('#city').empty();
                    $.each(data,function (index,subcatObj){
                        $('#city').append('<option value="'+subcatObj.id+'">'+subcatObj.name+'</option>');
                    })
                })
            })


        </script>

        <div class="form-group">
            <label for="exampleInputFile">Текст анкеты:</label>
            <textarea  name="description" id="description"  required> {{old('description')}}</textarea>
        </div>
        @if($errors->has('description'))
            <font color="red">  <p class="errors">{{$errors->first('description')}}</p></font>
        @endif

        <label>Выберите заглавную фотографию</label>

        <input type="file"  id="file" accept="image/*"  name="file" value="{{ old('file')}}" required>
        @if($errors->has('file'))
            <font color="red"><p>  {{$errors->first('file')}}</p></font>
        @endif

        <br><br>
        <script type="text/javascript" src="{{ asset('public/js/tinymce/tinymce.min.js') }}"></script>
        <script type="text/javascript">
            tinymce.init({
                selector : "textarea",
                plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
                toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            });

        </script>
        <script type="text/javascript">
            function isNumber(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
            function isNumber2(evt) {
                evt = (evt) ? evt : window.event;
                return false;

            }
        </script>
        <br>

        <!-- $('#textarea_id').tinymce().save();-->

        <label>Выберите фотографии для галереи(можно больше одной)</label>
        <input required type="file"  class="form-control" name="images[]" accept="image/*" placeholder="Фотографии" multiple >

        <input type="hidden" value="{{csrf_token()}}" name="_token">

        <script type="text/javascript">
            $(document).ready(function() {
                $("ul.dropdown-menu input[type=checkbox]").each(function() {
                    $(this).change(function() {
                        var line = "";
                        $("ul.dropdown-menu input[type=checkbox]").each(function() {
                            if($(this).is(":checked")) {
                                line += $("+ span", this).text() + ";";
                            }
                        });
                        $("input.form-control").val(line);
                    });
                });
            });

        </script>

        <script type="text/javascript" src="{{ asset('public/js/tinymce/tinymce.min.js') }}"></script>
        <script type="text/javascript">
            tinymce.init({
                selector : "textarea",
                plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste"],
                toolbar : "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            });
        </script>
        <br>
        <button type="submit" class="btn btn-default">Разместить анкету</button>
    </form>

    <hr>
    <a class="button blue" href="{{route('main')}}" role="link">К списку анкет</a>

    <script>
        //Код jQuery, установливающий маску для ввода телефона элементу input
        //1. После загрузки страницы,  когда все элементы будут доступны выполнить...
        $(function(){
            //2. Получить элемент, к которому необходимо добавить маску
            $("#phone").mask("8(999) 999-9999");
        });
    </script>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

@endsection
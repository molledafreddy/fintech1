
	 <header id="header" class="menu">
		<div class="header-content clearfix">
		  <a  href="{{route('home')}}">
		      <!-- <img src="{{ url('assets/logo.png') }}" style="width: 50%; height: 50%;"> -->
		      {{ config('', 'Fintech')  }}
		  </a>
		  <nav class="navigation" role="navigation">
		    @guest
		      <li><a class="dropdown-toggle" data-toggle="dropdown" href="{{ route('login') }}">Ir a inicio</a>		          
		      </li>
		      @else
		    <ul class="primary-nav">
		      <li><a href="{{ route('employee/index') }}">Gestión Empleados</a></li>
		      @if(Auth::user()->role=='administrador')
		      	<li><a href="{{ route('transfer-file/index') }}">Gestión Transferencias</a></li>
		      	<li><a href="{{ route('account-file/index') }}">Dar de Alta</a></li>
		      @else 
		      <li><a href="{{ route('contact/index') }}">Contacto</a></li>
		      @endif
		      <!-- <li><a href="#teams">Nuestro Equipo</a></li>-->
		      <li><a href="{{ route('home')}}">Administración</a></li> 
		      <li class="dropdown">
		        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
		            {{ Auth::user()->name}} <span class="caret"></span>
		        </a>
		        <ul class="dropdown-menu">
		            <li>
		              <a href="{{ route('logout') }}"
		                  onclick="event.preventDefault();
		                           document.getElementById('logout-form').submit();">
		                  Logout
		              </a>
		              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
		                  {{ csrf_field() }}
		              </form>
		            </li>
		        </ul>
		      </li> 
		    </ul>
		    @endguest
		  </nav>
		  <!--<a href="#" class="nav-toggle">Menu<span></span></a> </div> -->
		</div> 
	</header>
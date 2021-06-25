import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import {FormsModule} from '@angular/forms';
import {HttpClientModule } from '@angular/common/http';
import {routing, appRoutingProviders } from './app.routing';



import { AppComponent } from './app.component';
import{LoginComponent } from './components/login/login.component';
import{RegisterComponent } from './components/register/register.component';
import {DefaultComponent} from './components/default/default.component';
import { CarEditComponent } from './components/car-edit/car-edit.component';
import { CarDetailComponent } from './components/car-detail/car-detail.component';
import { CarNewComponent } from './components/car-new/car-new.component';


@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    DefaultComponent,
    CarEditComponent,
    CarDetailComponent,
    CarNewComponent

  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    routing
  ],
  providers: [
appRoutingProviders
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }

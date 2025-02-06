import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../services/auth.service';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { AlertsService } from '../../services/alerts.service';

@Component({
  selector: 'app-login',
  standalone: false,
  
  templateUrl: './login.component.html',
  styleUrl: './login.component.scss'
})
export class LoginComponent implements OnInit {
  public showLogin = true;

  public loginForm: FormGroup;

  constructor(private formBuilder: FormBuilder, private auth: AuthService, private alert: AlertsService) {
    this.loginForm = this.formBuilder.group({
      email: new FormControl('',[Validators.email, Validators.required]),
      password: new FormControl('', [Validators.required]),
    });
  }

  ngOnInit(): void {
    if(localStorage.getItem('token')) {
      this.showLogin = false;
    }
  }

  login() {
    this.alert.clearMessage();

    if(this.loginForm.valid) {
      this.auth.login(this.loginForm.value).subscribe((response: any) => {
        // If there is the token, store it
        if(typeof response.token !== 'undefined') {
          this.showLogin = false;
          localStorage.setItem('token', response.token);
          
          this.alert.showSuccess(response.message);
        }
        
      });
    }
    else {
      this.alert.showError('Invalid form');
    }
  }

  logout(): void {
    this.alert.clearMessage();

    this.auth.logout().subscribe((response: any) => {
      this.alert.showSuccess(response.message);
    });

    localStorage.removeItem('token');
    this.showLogin = true;
  }
}

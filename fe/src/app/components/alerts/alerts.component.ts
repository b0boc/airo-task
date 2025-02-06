import { Component, OnInit } from '@angular/core';
import { AlertsService } from '../../services/alerts.service';

@Component({
  selector: 'app-alerts',
  standalone: false,
  
  templateUrl: './alerts.component.html',
  styleUrl: './alerts.component.scss'
})
export class AlertsComponent implements OnInit {

  alert: any;

  constructor(private alertService: AlertsService) {
    this.alert = {
      type: '',
      message: ''
    };
  }

  ngOnInit(): void {
    this.alertService.alert$.subscribe((alert: any) => {
      this.alert = alert;
    });
  }
}

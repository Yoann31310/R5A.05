package tp1.entity;

import java.time.LocalDateTime;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;

@Entity // This tells Hibernate to make a table out of this class
public class Matchs {
  @Id
  @GeneratedValue(strategy=GenerationType.AUTO)
  private Integer id;

  private LocalDateTime dateHeure;

  private String nomEquipeAdverse;

  private String lieu;

  private String adresse;

  private String resultat;

  public Integer getId() {
    return id;
  }

  public void setId(Integer id) {
    this.id = id;
  }

  public LocalDateTime getDateHeure() {
    return dateHeure;
  }

  public void setDateHeure(LocalDateTime dateHeure) {
    this.dateHeure = dateHeure;
  }

  public String getNomEquipeAdverse() {
    return nomEquipeAdverse;
  }

  public void setNomEquipeAdverse(String nomEquipeAdverse) {
    this.nomEquipeAdverse = nomEquipeAdverse;
  }

  public String getLieu() {
    return lieu;
  }

  public void setLieu(String lieu) {
    this.lieu = lieu;
  }

  public String getAdresse() {
    return adresse;
  }

  public void setAdresse(String adresse) {
    this.adresse = adresse;
  }

  public String getResultat() {
    return resultat;
  }

  public void setResultat(String resultat) {
    this.resultat = resultat;
  }

  @Override
  public String toString() {
    return "{" +
        " id='" + getId() + "'" +
        " dateHeure='" + getDateHeure() + "'" +
        " nomEquipeAdverse='" + getNomEquipeAdverse() + "'" +
        " lieu='" + getLieu() + "'" +
        " adresse='" + getAdresse() + "'" +
        " resultat='" + getResultat() + "'" +
        "}";
  }
}

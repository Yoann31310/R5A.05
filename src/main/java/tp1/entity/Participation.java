package tp1.entity;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.ManyToOne;

@Entity // This tells Hibernate to make a table out of this class
public class Participation {
  @Id
  @GeneratedValue(strategy=GenerationType.AUTO)
  private Integer id;

  @ManyToOne
  private Joueur joueur;

  @ManyToOne
  private Matchs match;

  private String feuilleMatch; // "titulaire" ou "remplaçant"

  private String poste;

  private boolean estCapitaine;

  private Integer evaluation; // note sur 5

  private String commentaire;

  public Integer getId() {
    return id;
  }

  public void setId(Integer id) {
    this.id = id;
  }

  public Joueur getJoueur() {
    return joueur;
  }

  public void setJoueur(Joueur joueur) {
    this.joueur = joueur;
  }

  public Matchs getMatch() {
    return match;
  }

  public void setMatch(Matchs match) {
    this.match = match;
  }

  public String getFeuilleMatch() {
    return feuilleMatch;
  }

  public void setFeuilleMatch(String feuilleMatch) {
    this.feuilleMatch = feuilleMatch;
  }

  public String getPoste() {
    return poste;
  }

  public void setPoste(String poste) {
    this.poste = poste;
  }

  public boolean isEstCapitaine() {
    return estCapitaine;
  }

  public void setEstCapitaine(boolean estCapitaine) {
    this.estCapitaine = estCapitaine;
  }

  public Integer getEvaluation() {
    return evaluation;
  }

  public void setEvaluation(Integer evaluation) {
    this.evaluation = evaluation;
  }

  public String getCommentaire() {
    return commentaire;
  }

  public void setCommentaire(String commentaire) {
    this.commentaire = commentaire;
  }

  public boolean estTitulaire() {
    if (this.feuilleMatch == null) {
      return false;
    }
    return this.feuilleMatch.equalsIgnoreCase("titulaire");
  }

  public boolean estRemplacant() {
    if (this.feuilleMatch == null) {
      return false;
    }
    return this.feuilleMatch.equalsIgnoreCase("remplaçant") || this.feuilleMatch.equalsIgnoreCase("remplacant");
  }

  @Override
  public String toString() {
    return "{" +
        " id='" + getId() + "'" +
        ", joueur='" + (getJoueur() != null ? getJoueur().getId() : "null") + "'" +
        ", match='" + (getMatch() != null ? getMatch().getId() : "null") + "'" +
        ", feuilleMatch='" + getFeuilleMatch() + "'" +
        ", poste='" + getPoste() + "'" +
        ", estCapitaine='" + isEstCapitaine() + "'" +
        ", evaluation='" + getEvaluation() + "'" +
        ", commentaire='" + getCommentaire() + "'" +
        "}";
  }
}
